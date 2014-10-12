<?php

  function create_subsidy($binet, $wave, $amount, $purpose = "") {
    $sql = "INSERT INTO subsidy(binet, wave, requested_amount, purpose, created_by)
            VALUES(:binet, :wave, :amount, :purpose, :student)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':wave', $wave, PDO::PARAM_INT);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute(array(
      ':purpose' => $purpose
    ));
    $subsidy = $req->fetch(PDO::FETCH_ASSOC);
    return $subsidy["id"];
  }

  function select_subsidy($subsidy) {
    $sql = "SELECT *
            FROM subsidy
            WHERE id = :subsidy
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
  }

  function update_subsidy($subsidy, $hash) {
    foreach ($hash as $column => $value) {
      if (in_array($column, array("requested_amount", "purpose", "granted_amount", "explanation"))) {
        $sql = "UPDATE subsidy
                SET :column = :value
                WHERE id = :subsidy
                LIMIT 1";
        $req = Database::get()->prepare($sql);
        $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
        if (in_array($column, array("requested_amount", "granted_amount"))) {
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

  function add_spending_subsidy($spending, $amount, $subsidy) {
    $sql = "INSERT INTO spending_subsidy(spending, subsidy, amount)
            VALUES(:spending, :subsidy, :amount)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->execute();
  }

  function remove_spending_subsidy($spending, $subsidy) {
    $sql = "DELETE
            FROM spending_subsidy
            WHERE subsidy = :subsidy AND spending = :spending";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
  }

  function select_subsidies_spending($spending) {
    $sql = "SELECT *
            FROM spending_subsidy
            INNER JOIN subsidy
            ON subsidy.id = spending_subsidy.subsid
            WHERE spending_subsidy.spending = :spending";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_subsidies_tag_array($tags, $validated = true) {
    $sql = "SELECT *
            FROM subsidy
            WHERE true";
    $i = 0;
    foreach($tags as $tag) {
      $sql .= " AND EXISTS (SELECT * FROM subsidy_tag WHERE subsidy_tag.subsidy = subsidy.id AND subsidy_tag.tag = :tag".$i.")";
      $i++;
      $bindparams[":tag".$i] = $tag;
    }
    if ($validated) {
      $sql .= " AND validated_by != NULL";
    }
    $req = Database::get()->prepare($sql);
    foreach($bindparams as $key => $value) {
      $req->bindParam($key, $value, PDO::PARAM_INT);
    }
    $req->execute();
    return $req->fetchAll();
  }

  function consumed_amount_subsidy($subsidy) {
    $sql = "SELECT SUM(amount) as amount
            FROM spending_subsidy
            WHERE subsidy = :subsidy";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetch(PDO::FETCH_ASSOC);
    return $res["amount"] or 0;
  }

  function select_subsidies_origin($binet, $validated = true) {
    $sql = "SELECT *
            FROM subsidy
            WHERE origin = :binet";
    if ($validated) {
      $sql .= " AND validated_by != NULL";
    }
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_subsidies_beneficiary($binet, $validated = true) {
    $sql = "SELECT *
            FROM subsidy
            WHERE beneficiary = :binet";
    if ($validated) {
      $sql .= " AND validated_by != NULL";
    }
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_subsidies($criteria) {
    return select_entries("subsidy",
                          array("binet", "wave", "requested_amount", "granted_amount", "created_by"),
                          array(),
                          $criteria);
  }
