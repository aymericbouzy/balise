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
