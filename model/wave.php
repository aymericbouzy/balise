<?php

  function create_wave($binet, $term, $submission_date, $expiry_date) {
    $values["binet"] = $binet;
    $values["term"] = $term;
    $values["submission_date"] = $submission_date;
    $values["expiry_date"] = $expiry_date;
    return create_entry(
      "wave",
      array("binet", "term"),
      array("submission_date", "expiry_date"),
      $values
    );
  }

  function select_wave($wave, $fields = NULL) {
    return select_entry(
      "wave",
      array("id", "binet", "term", "submission_date", "expiry_date", "published"),
      $wave,
      $fields
    );
  }

  // TODO: selection by : total_requested_amount, total_granted_amount, total_spent_amount
  function select_waves($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries("wave",
                          array("binet", "term", "published"),
                          array("submission_date", "expiry_date"),
                          $criteria,
                          $order_by,
                          $ascending);
  }

  function publish_wave($wave) {
    $sql = "UPDATE wave
            SET published = 1
            WHERE id = :wave
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':wave', $wave, PDO::PARAM_INT);
    $req->execute();
  }

  function get_used_amount_wave($wave) {
    $amount = 0;
    foreach(select_requests(array("wave" => $wave)) as $request) {
      foreach(select_subsidies(array("request" => $request["id"])) as $subsidy) {
        $subsidy = select_subsidy($subsidy["id"], array("id", "budget"));
        foreach(get_subsidized_amount_used_details_budget($subsidy["budget"]) as $budget_subsidy) {
          if ($budget_subsidy["id"] == $subsidy["id"]) {
            $amount += $budget_subsidy["used_amount"];
          }
        }
      }
    }
    return $amount;
  }

  function get_granted_amount_wave($wave) {
    $amount = 0;
    foreach(select_requests(array("wave" => $wave)) as $request) {
      $amount += get_granted_amount_request($request["id"]);
    }
    return $amount;
  }
