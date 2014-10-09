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
    foreach ($hash as $column => $value) {
      if (in_array($column, array("amount", "type", "comment"))) {
        $sql = "UPDATE income
                SET :column = :value
                WHERE id = :income
                LIMIT 1";
        $req = Database::get()->prepare($sql);
        $req->bindParam(':income', $income, PDO::PARAM_INT);
        if (in_array($column, array("amount", "type"))) {
          $req->bindParam(':'.$value, $value, PDO::PARAM_INT);
          $req->execute(array(
            (':'.$column) => $column
          ));
        } else {
          $req->execute(array(
            (':'.$column) => $column,
            (':'.$value) => $value
          ));
        }
      }
    }
  }

  function select_incomes_binet($binet, $validated = true) {
    $sql = "SELECT *
            FROM income
            WHERE binet = :binet";
    if ($validated) {
      $sql .= " AND validated_by != NULL";
    }
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }
