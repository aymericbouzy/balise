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
    return select_entry("operation", $operation, $fields);
  }

  function validate_operation($operation) {
    $sql = "UPDATE operation
            SET binet_validation_by = :student
            WHERE id = :spending
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->execute();
  }

  function validate_kes_spending($spending) {
    $sql = "UPDATE spending
            SET kes_validation_by = :student
            WHERE id = :spending
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->execute();
  }

  function update_spending($spending, $hash) {
    update_entry("spending",
                  array("amount", "paid_by"),
                  array("bill", "comment"),
                  $spending,
                  $hash);
  }

  function select_spendings($criteria, $order_by = NULL, $ascending = true) {
    if (!isset($criteria["kes_validation_by"])) {
      $criteria["kes_validation_by"] = array("!=", NULL)
    }
    if (!isset($criteria["binet_validation_by"])) {
      $criteria["binet_validation_by"] = array("!=", NULL);
    }
    return select_entries("spending",
                          array("amount", "binet", "term", "created_by", "binet_validation_by", "kes_validation_by", "paid_by"),
                          array("bill", "date"),
                          $criteria,
                          $order_by,
                          $ascending);
  }

  function add_budgets_spending($spending, $amounts) {
    foreach ($amounts as $budget => $amount) {
      $sql = "INSERT INTO spending_budget(spending, budget, amount)
              VALUES(:spending, :budget, :amount)";
      $req->bindParam(':spending', $income, PDO::PARAM_INT);
      $req->bindParam(':budget', $budget, PDO::PARAM_INT);
      $req->bindParam(':amount', $amount, PDO::PARAM_INT);
      $req->execute();
    }
  }

  function remove_budgets_spending($spending) {
    $sql = "DELETE
            FROM spending_budget
            WHERE spending = :spending";
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->execute();
  }
