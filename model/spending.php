<?php

  function create_spending($amount, $binet, $comment = "", $paid_by = 0, $bill = 0) {
    $sql = "INSERT INTO spending(date, amount, binet, bill, created_by, paid_by, comment)
            VALUES(CURDATE(), :amount, :binet, :bill, :student, :paid_by, :comment)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->bindParam(':paid_by', $paid_by, PDO::PARAM_INT);
    $req->execute(array(
      ':bill' => $bill,
      ':comment' => $comment
    ));
    $spending = $req->fetch(PDO::FETCH_ASSOC);
    return $spending["id"];
  }

  function select_spending($spending, $fields = NULL) {
    return select_entry("spending", $spending, $fields);
  }

  function validate_spending($spending) {
    $sql = "UPDATE spending
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

  /*
  function select_spendings_subsidy($subsidy, $kes_validated = true, $binet_validated = true) {
    $sql = "SELECT *
            FROM spending_subsidy
            INNER JOIN spending
            ON spending.id = spending_subsidy.spending
            WHERE spending_subsidy.subsidy = :subsidy";
    if ($kes_validated) {
      $sql .= " AND spending.kes_validated_by != NULL";
    }
    if ($binet_validated) {
      $sql .= " AND spending.binet_validated_by != NULL";
    }
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }
  */

  function select_spendings($criteria) {
    if (!isset($criteria["kes_validation_by"])) {
      $criteria["kes_validation_by"] = array("!=", NULL)
    }
    if (!isset($criteria["binet_validation_by"])) {
      $criteria["binet_validation_by"] = array("!=", NULL);
    }
    return select_entries("spending",
                          array("amount", "binet", "term", "created_by", "binet_validation_by", "kes_validation_by", "paid_by"),
                          array("bill", "date"),
                          $criteria);
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
