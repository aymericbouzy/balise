<?php

  function create_budget($binet, $term, $amount, $label) {
    $values["binet"] = $binet;
    $values["term"] = $term;
    $values["amount"] = $amount;
    $values["label"] = $label;
    return create_entry(
      "budget",
      array("binet", "term", "amount"),
      array("label"),
      $values
    );
  }

  function select_budget($budget, $fields = array()) {
    $id = $budget;
    $budget = select_entry(
      "budget",
      array("id", "binet", "amount", "term", "label"),
      $budget,
      $fields
    );
    foreach ($fields as $field) {
      switch ($field) {
      case "real_amount":
        $budget[$field] = get_real_amount_budget($id);
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
      array("binet", "amount", "term"),
      array(),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function update_budget($budget, $hash) {
    update_entry("budget",
                  array("amount"),
                  array("label"),
                  $budget,
                  $hash);
  }

  function get_real_amount_budget($budget) {
    $sql = "SELECT SUM(operation_budget.amount) as real_amount
            FROM operation_budget
            INNER JOIN operation
            ON operation.id = operation_budget.operation
            WHERE operation_budget.budget = :budget AND operation.kes_validation_by != NULL";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC)["real_amount"];
  }

  function get_subsidized_amount_granted_budget($budget) {
    $sql = "SELECT SUM(subsidy.granted_amount) as subsidized_amount
            FROM subsidy
            INNER JOIN request
            ON request.id = subsidy.request
            INNER JOIN wave
            ON wave.id = request.wave
            WHERE wave.published = 1 AND subsidy.budget = :budget";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC)["subsidized_amount"];
  }

  function get_subsidized_amount_requested_budget($budget) {
    $sql = "SELECT SUM(subsidy.requested_amount) as subsidized_amount
            FROM subsidy
            INNER JOIN request
            ON request.id = subsidy.request
            INNER JOIN wave
            ON wave.id = request.wave
            WHERE wave.published = 1 AND subsidy.budget = :budget";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC)["subsidized_amount"];
  }

  // To order subsidies in get_subsidized_amount_used_details_budget
  function sort_by_date($s1, $s2) {
    return strcmp($s1["expiry_date"], $s2["expiry_date"]);
  }

  function get_subsidized_amount_used_details_budget($budget) {
    $subsidies = select_subsidies(array("budget" => $budget));
    foreach($subsidies as $index => $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "granted_amount", "wave"));
      $subsidies[$index]["expiry_date"] = select_wave($subsidy["wave"], array("expiry_date"))["expiry_date"];
      $subsidies[$index]["used_amount"] = 0;
    }
    usort($subsidies, "sort_by_date");
    foreach(select_operations_budget($budget) as $operation) {
      $operation = select_operation($operation["id"], array("date", "amount"));
      $i = 0;
      while(isset($subsidies[$i]) && $operation["amount"] < 0) {
        if ($operation["date"] < $subsidies[$i]["expiry_date"] && $subsidies[$i]["granted_amount"] > $subsidies[$i]["used_amount"]) {
          $amount = min($operation["amount"], $subsidies[$i]["granted_amount"] - $subsidies[$i]["used_amount"]);
          $operation["amount"] -= $amount;
          $subsidies[$i]["used_amount"] += $amount;
        }
        $i++;
      }
    }
    return $subsidies;
  }

  function get_subsidized_amount_used_budget($budget) {
    return sum_array(get_subsidized_amount_used_details_budget($budget), "used_amount");
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
