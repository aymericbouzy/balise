<?php

  function create_income($amount, $binet, $type, $comment) {
    $sql = "INSERT INTO income(date, amount, type, created_by, comment)
            VALUES(CURDATE(), :amount, :binet, :type, :student, :comment)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':type', $type, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute(array(
      ':comment' => $comment
    ));
    $income = $req->fetch(PDO::FETCH_ASSOC);
    return $income["id"];
  }

  function select_income($income, $fields = NULL) {
    return select_entry("income", $income, $fields);
  }

  function validate_income($income) {
    $sql = "UPDATE income
            SET validation_by = :student
            WHERE id = :income
            LIMIT 1";
    $req->bindParam(':income', $income, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
  }

  function update_income($income, $hash) {
    update_entry("income",
                  array("amount", "type"),
                  array("comment"),
                  $income,
                  $hash);
  }

  function select_incomes($criteria, $order_by = NULL, $ascending = true) {
    if (!isset($criteria["kes_validation_by"])) {
      $criteria["kes_validation_by"] = array("!=", NULL);
    }
    return select_entries("income",
                          array("amount", "binet", "term", "type", "created_by", "kes_validation_by"),
                          array("date"),
                          $criteria,
                          $order_by,
                          $ascending);
  }

  function add_budgets_income($income, $amounts) {
    foreach ($amounts as $budget => $amount) {
      $sql = "INSERT INTO income_budget(income, budget, amount)
              VALUES(:income, :budget, :amount)";
      $req->bindParam(':income', $income, PDO::PARAM_INT);
      $req->bindParam(':budget', $budget, PDO::PARAM_INT);
      $req->bindParam(':amount', $amount, PDO::PARAM_INT);
      $req->execute();
    }
  }

  function remove_budgets_income($income) {
    $sql = "DELETE
            FROM income_budget
            WHERE income = :income";
    $req->bindParam(':income', $income, PDO::PARAM_INT);
    $req->execute();
  }
