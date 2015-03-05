<?php

  function create_budget($binet, $term, $amount, $label, $subsidized_amount = NULL) {
    $values["binet"] = $binet;
    $values["term"] = $term;
    $values["amount"] = $amount;
    $values["label"] = $label;
    if (isset($subsidized_amount)) {
      $values["subsidized_amount"] = $subsidized_amount;
    }
    return create_entry(
      "budget",
      array("binet", "term", "amount", "subsidized_amount"),
      array("label"),
      $values
    );
  }

  function select_budget($budget, $fields = array()) {
    $id = $budget;
    $present_virtual_fields = array_intersect($fields, array("real_amount"));
    if (!is_empty($present_virtual_fields)) {
      $fields = array_merge($fields, array("amount"));
    }
    $budget = select_entry(
      "budget",
      array("id", "binet", "amount", "term", "label", "subsidized_amount"),
      $budget,
      $fields
    );
    foreach ($fields as $field) {
      switch ($field) {
      case "real_amount":
        $sign = $budget["amount"] > 0 ? 1 : -1;
        $budget[$field] = get_real_amount_budget($id) * $sign;
        break;
      case "subsidized_amount_requested":
        $budget[$field] = get_subsidized_amount_requested_budget($id);
        break;
      case "subsidized_amount_granted":
        $budget[$field] = get_subsidized_amount_granted_budget($id);
        break;
      case "subsidized_amount_used":
        $budget[$field] = get_subsidized_amount_used_budget($id);
        break;
      case "subsidized_amount_available":
        $budget[$field] = get_subsidized_amount_available_budget($id);
        break;
      }
    }
    return $budget;
  }

  function exists_budget($budget) {
    return select_budget($budget, array("id")) ? true : false;
  }


  function select_budgets($criteria, $order_by = NULL, $ascending = true) {
    return select_entries(
      "budget",
      array("binet", "amount", "term", "subsidized_amount"),
      array(),
      array("real_amount", "subsidized_amount_requested", "subsidized_amount_granted", "subsidized_amount_used"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function update_budget($budget, $hash) {
    update_entry("budget",
                  array("amount", "subsidized_amount"),
                  array("label"),
                  $budget,
                  $hash);
  }

  function delete_budget($budget) {
    delete_entry("budget", $budget);
  }

  function get_real_amount_budget($budget) {
    $all_operations = ids_as_keys(select_operations_budget($budget));
    $filtered_operations = array_merge(
      filter_entries($all_operations, "operation", array("state", "needs_validation"), array("needs_validation" => false, "state" => "accepted")),
      filter_entries($all_operations, "operation", array("state"), array("state" => "validated"))
    );
    $operations = array();
    foreach(ids_as_keys($filtered_operations) as $id => $operation) {
      $operations[] = $all_operations[$id];
    }
    return sum_array($operations, "amount");
  }

  function get_subsidized_amount_granted_budget($budget) {
    $sql = "SELECT SUM(subsidy.granted_amount) as sum_granted_amount
            FROM subsidy
            INNER JOIN request
            ON request.id = subsidy.request
            INNER JOIN wave
            ON wave.id = request.wave
            WHERE wave.published = 1 AND subsidy.budget = :budget";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC)["sum_granted_amount"];
  }

  function get_subsidized_amount_requested_budget($budget) {
    $sql = "SELECT SUM(subsidy.requested_amount) as sum_requested_amount
            FROM subsidy
            INNER JOIN request
            ON request.id = subsidy.request
            INNER JOIN wave
            ON wave.id = request.wave
            WHERE wave.published = 1 AND subsidy.budget = :budget";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC)["sum_requested_amount"];
  }

  function get_subsidized_amount_used_details_budget($budget) {
    $subsidies = array();
    foreach(select_subsidies(array("budget" => $budget), "expiry_date", false) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "granted_amount", "wave", "request", "expiry_date"));
      $subsidy["accepted"] = select_request($subsidy["request"], array("state"))["state"] == "accepted";
      $subsidy["used_amount"] = 0;
      $subsidy["granted_amount"] = $subsidy["granted_amount"];
      set_if_not_set($subsidy["granted_amount"], 0);
      $subsidies[] = $subsidy;
    }
    foreach(select_operations_budget($budget) as $operation) {
      $operation = select_operation($operation["id"], array("payment_date", "amount", "state"));
      if ($operation["state"] == "validated") {
        $i = 0;
        while (isset($subsidies[$i]) && $operation["amount"] < 0) {
          if ($subsidies[$i]["accepted"] && $operation["payment_date"] < $subsidies[$i]["expiry_date"] && $subsidies[$i]["granted_amount"] > $subsidies[$i]["used_amount"]) {
            $amount = min(-$operation["amount"], $subsidies[$i]["granted_amount"] - $subsidies[$i]["used_amount"]);
            $operation["amount"] -= $amount;
            $subsidies[$i]["used_amount"] += $amount;
          }
          $i++;
        }
      }
    }
    return $subsidies;
  }

  function get_subsidized_amount_used_budget($budget) {
    return sum_array(get_subsidized_amount_used_details_budget($budget), "used_amount");
  }

  function get_subsidized_amount_available_budget($budget) {
    $amount = 0;
    foreach (select_subsidies(array("budget" => $budget)) as $subsidy) {
      $amount += select_subsidy($subsidy["id"], array("available_amount"))["available_amount"];
    }
    return $amount;
  }

  function select_budgets_operation($operation) {
    $sql = "SELECT budget as id, amount
            FROM operation_budget
            WHERE operation = :operation";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }
