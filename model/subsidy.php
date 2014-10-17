<?php

  function create_subsidy($budget, $wave, $amount, $purpose = "") {
    $sql = "INSERT INTO subsidy(budget, wave, requested_amount, purpose, created_by)
            VALUES(:budget, :wave, :amount, :purpose, :student)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':budget', $budget, PDO::PARAM_INT);
    $req->bindParam(':wave', $wave, PDO::PARAM_INT);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute(array(
      ':purpose' => $purpose
    ));
    $subsidy = $req->fetch(PDO::FETCH_ASSOC);
    return $subsidy["id"];
  }

  function select_subsidy($subsidy, $fields = NULL) {
    return select_entry("subsidy", $subsidy, $fields);
  }

  function update_subsidy($subsidy, $hash) {
    update_entry("subsidy",
                  array("requested_amount", "granted_amount"),
                  array("purpose", "explanation"),
                  $subsidy,
                  $hash);
  }

  function get_consumed_amount_subsidy($subsidy) {
    $subsidy = select_subsidy($subsidy);
    $consumed_amount = get_real_amount_budget($subsidy["budget"]);
    $subsidized_amount = get_subsidized_amount_budget($subsidy["budget"]);
    if ($consumed_amount >= $subsidized_amount) {
      return $subsidy["granted_amount"];
    } else {
      return $subsidy["granted_amount"] * $consumed_amount / $subsidized_amount;
    }
  }

  function select_subsidies($criteria, $order_by = NULL, $ascending = true) {
    return select_entries("subsidy",
                          array("binet", "wave", "requested_amount", "granted_amount", "created_by"),
                          array(),
                          $criteria,
                          $order_by,
                          $ascending);
  }
