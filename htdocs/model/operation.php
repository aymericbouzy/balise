<?php

  function create_operation($binet, $term, $amount, $type, $optional_values = array()) {
    $values["binet"] = $binet;
    $values["term"] = $term;
    $values["amount"] = $amount;
    $values["type"] = $type;
    $values["created_by"] = $_SESSION["student"];
    $values["date"] = array("date", "CURDATE()");
    return create_entry(
      "operation",
      array("binet", "term", "amount", "created_by", "paid_by", "type"),
      array("date", "bill", "payment_ref", "comment", "bill_date", "payment_date"),
      array_merge($optional_values, $values)
    );
  }

  function select_operation($operation, $fields = array()) {
    $present_virtual_fields = array_intersect($fields, array("state", "needs_validation"));
    if (!is_empty($present_virtual_fields)) {
      $fields = array_unique(array_merge(array("kes_validation_by", "binet_validation_by", "id", "needs_validation"), $fields));
    }
    $operation = select_entry(
      "operation",
      array("id", "binet", "term", "amount", "created_by", "paid_by", "type", "date", "bill", "bill_date", "payment_ref", "payment_date", "comment", "binet_validation_by", "kes_validation_by"),
      $operation,
      $fields
    );
    if (in_array("needs_validation", $fields)) {
      $operation["needs_validation"] = false;
      $requests = array();
      foreach (select_budgets_operation($operation["id"]) as $budget) {
        foreach (select_subsidies(array("budget" => $budget["id"], "granted_amount" => array(">", 0))) as $subsidy) {
          $subsidy = select_subsidy($subsidy["id"], array("request"));
          $requests[] = $subsidy["request"];
        }
        foreach (array_unique($requests) as $request) {
          $request = select_request($request, array("state"));
          $operation["needs_validation"] = $operation["needs_validation"] || $request["state"] == "accepted";
        }
      }
    }
    if (in_array("state", $fields)) {
      $operation["state"] =
        !is_empty($operation["kes_validation_by"]) ?
          "validated" :
          (is_empty($operation["binet_validation_by"]) ?
            "suggested" :
            ($operation["needs_validation"] ?
              "waiting_validation" :
              "accepted"));
    }
    return $operation;
  }

  function exists_operation($operation) {
    return select_operation($operation) ? true : false;
  }

  function validate_operation($operation) {
    $sql = "UPDATE operation
            SET binet_validation_by = :student
            WHERE id = :operation
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->bindValue(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }

  function kes_validate_operation($operation) {
    $sql = "UPDATE operation
            SET kes_validation_by = :student
            WHERE id = :operation
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->bindValue(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }

  function kes_reject_operation($operation) {
    $sql = "UPDATE operation
            SET binet_validation_by = NULL, kes_validation_by = NULL
            WHERE id = :operation
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }

  function update_operation($operation, $hash) {
    update_entry("operation",
                  array("amount", "paid_by", "binet", "term", "type"),
                  array("bill", "payment_ref", "comment", "bill_date", "payment_date"),
                  $operation,
                  $hash);
  }

  function select_operations($criteria = array(), $order_by = "date", $ascending = true) {
    set_if_not_set($criteria["state"], array("IN", array("accepted", "validated")));
    return select_entries(
      "operation",
      array("amount", "binet", "term", "created_by", "binet_validation_by", "kes_validation_by", "paid_by", "type"),
      array("bill", "date", "payment_ref", "bill_date", "payment_date"),
      array("state", "needs_validation"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function delete_operation($operation) {
    delete_entry("operation", $operation);
  }

  function select_operations_budget($budget) {
    $sql = "SELECT operation as id, amount
            FROM operation_budget
            WHERE budget = :budget";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_subsidies_and_requests_operation($operation) {
    $subsidies = array();
    foreach (select_budgets_operation($operation) as $budget) {
      $subsidies = array_merge($subsidies, select_subsidies(array("budget" => $budget["id"])));
    }
    $subsidies_by_request = array();
    foreach ($subsidies as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "request", "granted_amount"));
      $request = select_request($subsidy["request"], array("id", "state"));
      if ($subsidy["granted_amount"] > 0 && $request["state"] == "accepted") {
        $subsidies_by_request[$request["id"]][] = $subsidy["id"];
      }
    }
    return $subsidies_by_request;
  }

  function add_budgets_operation($operation, $amounts) {
    foreach ($amounts as $budget => $amount) {
      $sql = "INSERT INTO operation_budget(operation, budget, amount)
              VALUES(:operation, :budget, :amount)";
      $req = Database::get()->prepare($sql);
      $req->bindValue(':operation', $operation, PDO::PARAM_INT);
      $req->bindValue(':budget', $budget, PDO::PARAM_INT);
      $req->bindValue(':amount', $amount, PDO::PARAM_INT);
      $req->execute();
    }
  }

  function remove_budgets_operation($operation) {
    $sql = "DELETE
            FROM operation_budget
            WHERE operation = :operation";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }

  function count_pending_validations($binet, $term) {
    return count(pending_validations_operations($binet, $term));
  }

  function count_pending_validations_kes() {
    return count(kes_pending_validations_operations());
  }

  function pending_validations_operations($binet, $term) {
    return select_operations(array("binet" => $binet, "term" => $term, "state" => "suggested"), "date");
  }

  function kes_pending_validations_operations() {
    return select_operations(array("state" => "waiting_validation"), "date");
  }

  function concerned_subsidy_providers($operation) {
    $subsidy_providers = array();
    foreach (select_subsidies_and_requests_operation($operation) as $request => $subsidy) {
      $wave = select_request($request, array("wave"))["wave"];
      $subsidy_providers[] = select_wave($wave, array("binet"))["binet"];
    }
    return array_unique($subsidy_providers);
  }
