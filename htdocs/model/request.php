<?php

  function create_request($wave, $subsidies, $answer = "") {
    $values["wave"] = $wave;
    $values["answer"] = $answer;
    $request = create_entry(
      "request",
      array("wave"),
      array("answer"),
      $values
    );
    foreach($subsidies as $subsidy) {
      create_subsidy($subsidy["budget"], $request, $subsidy["amount"], $subsidy["optional_values"]);
    }
    return $request;
  }

  function select_request($request, $fields = array()) {
    $request = select_entry(
      "request",
      array("id", "wave", "answer", "sent"),
      $request,
      $fields
    );
    if (in_array("binet", $fields) || in_array("term", $fields)) {
      $subsidies = select_subsidies(array("request" => $request["id"]));
      $budget = select_budget($subsidies[0]["budget"]);
      if (in_array("binet", $fields)) {
        $request["binet"] = $budget["binet"];
      }
      if (in_array("term", $fields)) {
        $request["term"] = $budget["term"];
      }
    }
    return $request;
  }

  function exists_request($request) {
    return select_request($request) ? true : false;
  }

  function update_request($request, $hash) {
    update_entry("request",
                  array(),
                  array("answer"),
                  $request,
                  $hash);
  }

  function select_requests($criteria, $order_by = NULL, $ascending = true) {
    if (!isset($criteria["sent"])) {
      $criteria["sent"] = 1;
    }
    return select_entries(
      "request",
      array("wave", "sent"),
      array(),
      array("binet", "term"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function send_request($request) {
    $sql = "UPDATE request
            SET sent = 1
            WHERE id = :request
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':request', $request, PDO::PARAM_INT);
    $req->execute();
  }

  function get_subsidized_amount_used_request($request) {
    $amount = 0;
    foreach(select_subsidies(array("request" => $request)) as $subsidy) {
      $amount += get_used_amount_subsidy($subsidy);
    }
    return $amount;
  }

  function get_granted_amount_request($request) {
    $amount = 0;
    foreach(select_subsidies(array("request" => $request)) as $subsidy) {
      $amount += select_subsidy($subsidy["id"], array("granted_amount"))["granted_amount"];
    }
    return $amount;
  }

  function get_requested_amount_request($request) {
    $amount = 0;
    foreach(select_subsidies(array("request" => $request)) as $subsidy) {
      $amount += select_subsidy($subsidy["id"], array("requested_amount"))["requested_amount"];
    }
    return $amount;
  }
