<?php

  function create_wave($binet, $term, $submission_date, $expiry_date, $question) {
    $values["binet"] = $binet;
    $values["term"] = $term;
    $values["submission_date"] = $submission_date;
    $values["expiry_date"] = $expiry_date;
    $values["question"] = $question;
    return create_entry(
      "wave",
      array("binet", "term"),
      array("submission_date", "expiry_date", "question"),
      $values
    );
  }

  function select_wave($wave, $fields = array()) {
    if (in_array("state", $fields)) {
      $fields = array_merge(array("submission_date", "expiry_date", "published"), $fields);
    }
    if (!empty(array_intersect(array("requested_amount", "granted_amount", "used_amount"), $fields))) {
      $fields = array_merge(array("id"), $fields);
    }
    $wave = select_entry(
      "wave",
      array("id", "binet", "term", "submission_date", "expiry_date", "published", "question"),
      $wave,
      $fields
    );
    foreach ($fields as $field) {
      switch ($field) {
      case "requested_amount":
        $wave[$field] = get_requested_amount_wave($wave["id"]);
        break;
      case "granted_amount":
        $wave[$field] = get_granted_amount_wave($wave["id"]);
        break;
      case "used_amount":
        $wave[$field] = get_used_amount_wave($wave["id"]);
        break;
      case "state":
        $wave[$field] = $wave["submission_date"] > current_date() ? "submission" : ($wave["expiry_date"] > current_date() ? ($wave["published"] ? "distribution" : "deliberation") : "closed");
        break;
      }
    }
    return $wave;
  }

  function exists_wave($wave) {
    return select_wave($wave) ? true : false;
  }

  function select_waves($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries(
      "wave",
      array("binet", "term", "published"),
      array("submission_date", "expiry_date"),
      array("requested_amount", "granted_amount", "used_amount", "state"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function update_wave($wave, $hash) {
    update_entry(
      "wave",
      array(),
      array("submission_date", "expiry_date", "question"),
      $wave,
      $hash
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
      $amount += get_requested_amount_request($request["id"]);
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
