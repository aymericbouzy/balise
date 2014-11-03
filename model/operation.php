<?php

  function create_operation($binet, $term, $amount, $type, $optional_values = array()) {
    $values["binet"] = $binet;
    $values["term"] = $term;
    $values["amount"] = $amount;
    $values["type"] = $type;
    $values["created_by"] = $_SESSION["student"];
    $values["date"] = array("date", "CURDATE()");
    return create_operation(
      "operation",
      array("binet", "term", "amount", "created_by", "paid_by", "type"),
      array("date", "bill", "reference", "comment"),
      array_merge($values, $optional_values);
    );
  }

  function select_operation($operation, $fields = array()) {
    return select_entry(
      "operation",
      array("id", "binet", "term", "amount", "created_by", "paid_by", "type", "date", "bill", "reference", "comment", "binet_validation_by", "kes_validation_by"),
      $operation,
      $fields
    );
  }

  function validate_operation($operation) {
    $sql = "UPDATE operation
            SET binet_validation_by = :student
            WHERE id = :operation
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->bindParam(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }

  function validate_kes_operation($operation) {
    $sql = "UPDATE operation
            SET kes_validation_by = :student
            WHERE id = :operation
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->bindParam(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }

  function update_operation($operation, $hash) {
    update_entry("operation",
                  array("amount", "paid_by", "binet", "term", "type"),
                  array("bill", "reference", "comment"),
                  $operation,
                  $hash);
  }

  // TODO: selection by : subsidied, subsidied_percentage
  function select_operations($criteria, $order_by = NULL, $ascending = true) {
    if (!isset($criteria["kes_validation_by"])) {
      $criteria["kes_validation_by"] = array("!=", NULL)
    }
    if (!isset($criteria["binet_validation_by"])) {
      $criteria["binet_validation_by"] = array("!=", NULL);
    }
    return select_entries(
      "operation",
      array("amount", "binet", "term", "created_by", "binet_validation_by", "kes_validation_by", "paid_by", "type"),
      array("bill", "date", "reference"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function add_budgets_operation($operation, $amounts) {
    foreach ($amounts as $budget => $amount) {
      $sql = "INSERT INTO operation_budget(operation, budget, amount)
              VALUES(:operation, :budget, :amount)";
      $req->bindParam(':operation', $operation, PDO::PARAM_INT);
      $req->bindParam(':budget', $budget, PDO::PARAM_INT);
      $req->bindParam(':amount', $amount, PDO::PARAM_INT);
      $req->execute();
    }
  }

  function remove_budgets_operation($operation) {
    $sql = "DELETE
            FROM operation_budget
            WHERE operation = :operation";
    $req->bindParam(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
  }
