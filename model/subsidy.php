<?php

  function create_subsidy($origin, $beneficiary, $amount, $expiration_date = NULL, $purpose = "") {
    $sql = "INSERT INTO subsidy(origin, beneficiary, amount, expiration_date, purpose)
            VALUES(:origin, :beneficiary, :amount, :expiration_date, :purpose)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':origin', $origin, PDO::PARAM_INT);
    $req->bindParam(':beneficiary', $beneficiary, PDO::PARAM_INT);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->execute(array(
      ':purpose' => $purpose,
      ':expirationdate' => $expiration_date
    ));
    $subsidy = $req->fetch(PDO::FETCH_ASSOC);
    return $subsidy["id"];
  }
