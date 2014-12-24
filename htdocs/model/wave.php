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

  function select_wave($wave, $fields = array()) {
    if (in_array("open", $fields) && !in_array("submission_date", $fields)) {
      $fields[] = "submission_date";
    }
    $wave = select_entry(
      "wave",
      array("id", "binet", "term", "submission_date", "expiry_date", "published"),
      $wave,
      $fields
    );
    foreach ($fields as $field) {
      switch ($field) {
      case "request_amount":
        $wave[$field] = get_requested_amount_wave($wave);
        break;
      case "granted_amount":
        $wave[$field] = get_granted_amount_wave($wave);
        break;
      case "used_amount":
        $wave[$field] = get_used_amount_wave($wave);
        break;
      case "open":
        $wave[$field] = $wave["submission_date"] > date("Ymd");
      }
    }
    return $wave;
  }

  function select_waves($criteria = array(), $order_by = NULL, $ascending = true) {
    if (!isset($criteria["published"])) {
      $criteria["published"] = 1;
    }
    return select_entries(
      "wave",
      array("binet", "term", "published"),
      array("submission_date", "expiry_date"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function publish_wave($wave) {
    $sql = "UPDATE wave
            SET published = 1
            WHERE id = :wave
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':wave', $wave, PDO::PARAM_INT);
    $req->execute();
  }

  function get_requested_amount_wave($wave) {
    $amount = 0;
    foreach(select_requests(array("wave" => $wave)) as $request) {
      $amount += get_requested_amount_request($request);
    }
    return $amount;
  }

  function get_used_amount_wave($wave) {
    $amount = 0;
    foreach(select_requests(array("wave" => $wave)) as $request) {
      $amount += get_used_amount_request($request);
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
