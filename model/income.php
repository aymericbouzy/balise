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

  function select_income($income) {
    $sql = "SELECT *
            FROM income
            WHERE id = :income
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':income', $income, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
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

  function select_incomes($criteria) {
    return select_entries("income",
                          array("amount", "binet", "origin", "created_by", "kes_validation_by"),
                          array("date"),
                          $criteria);
  }
