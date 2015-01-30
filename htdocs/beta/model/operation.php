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
      array("date", "bill", "reference", "comment"),
      array_merge($optional_values, $values)
    );
  }

  function select_operation($operation, $fields = array()) {
    if (!empty(array_intersect($fields, array("state", "needs_validation")))) {
      $fields = array_merge(array("binet_validation_by", "kes_validation_by", "id", "needs_validation"), $fields);
    }
    $operation = select_entry(
      "operation",
      array("id", "binet", "term", "amount", "created_by", "paid_by", "type", "date", "bill", "reference", "comment", "binet_validation_by", "kes_validation_by"),
      $operation,
      $fields
    );
    if (in_array("needs_validation", $fields)) {
      $operation["needs_validation"] = false;
      if (empty($operation["kes_validation_by"])) {
        $subsidies = select_subsidies(array("operation" => $operation["id"]));
        if (!empty($subsidies)) {
          $requests = array();
          foreach ($subsidies as $subsidy) {
            $subsidy = select_subsidy($subsidy["id"], array("request"));
            $requests[$subsidy["request"]] = true;
          }
          foreach ($requests as $request => $present) {
            $request = select_request($request, array("state"));
            $operation["needs_validation"] = $operation["needs_validation"] || $request["state"] == "accepted";
          }
        }
      }
    }
    if (in_array("state", $fields)) {
      $operation["state"] =
        isset($operation["kes_validation_by"]) ?
          "validated" :
          (!isset($operation["binet_validation_by"]) ?
            "suggested" :
            $operation["needs_validation"] ?
              "waiting_validation" :
              "accepted");
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
            SET binet_validation_by = NULL
            WHERE id = :operation
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }

  function update_operation($operation, $hash) {
    update_entry("operation",
                  array("amount", "paid_by", "binet", "term", "type"),
                  array("bill", "reference", "comment"),
                  $operation,
                  $hash);
  }

  function select_operations($criteria = array(), $order_by = "date", $ascending = true) {
    set_if_not_set($criteria["state"], array("IN", "accepted", "validated"));
    // set_if_not_set($criteria["kes_validation_by"], array("IS", "NOT NULL"));
    // set_if_not_set($criteria["binet_validation_by"], array("IS", "NOT NULL"));

    return select_entries(
      "operation",
      array("amount", "binet", "term", "created_by", "binet_validation_by", "kes_validation_by", "paid_by", "type"),
      array("bill", "date", "reference"),
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
    return count(pending_validations_operations($binet, $term)) + ($binet == KES_ID ? count(kes_pending_validations_operations()) : 0);
  }

  function pending_validations_operations($binet, $term) {
    return select_operations(array("kes_validation_by" => array("IS", "NULL"), "binet_validation_by" => array("IS", "NULL"), "binet" => $binet, "term" => $term), "date");
  }

  function kes_pending_validations_operations() {
    return select_operations(array("kes_validation_by" => array("IS", "NULL"), "binet_validation_by" => array("IS NOT", "NULL")), "date");
  }
