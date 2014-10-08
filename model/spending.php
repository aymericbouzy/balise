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

  function select_spending($spending) {
    $sql = "SELECT *
            FROM spending
            WHERE id = :spending
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
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
    foreach ($hash as $column => $value) {
      if (in_array($column, array("amount", "bill", "paid_by", "comment"))) {
        $sql = "UPDATE spending
                SET :column = :value
                WHERE id = :spending
                LIMIT 1";
        $req = Database::get()->prepare($sql);
        $req->bindParam(':spending', $spending, PDO::PARAM_INT);
        if (in_array($column, array("amount"))) {
          $req->bindParam(':'+$value, $value, PDO::PARAM_INT);
          $req->execute(array(
            (':'+$column) => $column
          ));
        } else {
          $req->execute(array(
            (':'+$column) => $column,
            (':'+$value) => $value
          ));
        }
      }
    }
  }

  function select_spendings_subsidy($subsidy) {
    $sql = "SELECT *
            FROM spending_subsidy
            INNER JOIN spending
            ON spending.id = spending_subsidy.spending
            WHERE spending_subsidy.subsidy = :subsidy";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function spent_amount_tag_array($tags) {
    $sql = "SELECT SUM(spending.amount) as amount
            FROM spending
            INNER JOIN spending_tag
            ON spending.id = spending_tag.spending
            WHERE true";
    $i = 0;
    foreach($tags as $tag) {
      $sql .= " AND spending_tag.tag = :tag" + $i;
      $i++;
      $bindparams[":tag" + $i] = $tag;
    }
    $req = Database::get()->prepare($sql);
    foreach($bindparams as $key => $value) {
      $req->bindParam($key, $value, PDO::PARAM_INT);
    }
    $req->execute();
    $res = $req->fetch(PDO::FETCH_ASSOC);
    return $res["amount"] or 0;
  }
