<?php

  function create_subsidy($origin, $beneficiary, $amount, $expiration_date = NULL, $purpose = "") {
    $sql = "INSERT INTO subsidy(origin, beneficiary, amount, expiration_date, purpose, created_by, validation_by)
            VALUES(:origin, :beneficiary, :amount, :expiration_date, :purpose, :created_by, NULL)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':origin', $origin, PDO::PARAM_INT);
    $req->bindParam(':beneficiary', $beneficiary, PDO::PARAM_INT);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->bindParam(':created_by', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute(array(
      ':purpose' => $purpose,
      ':expirationdate' => $expiration_date
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

  function validate_subsidy($subsidy) {
    $sql = "UPDATE subsidy
            SET validation_by = :student
            WHERE id = :subsidy";
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
  }

  
