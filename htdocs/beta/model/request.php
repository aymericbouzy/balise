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
      create_subsidy($subsidy["budget"], $request, $subsidy["requested_amount"], $subsidy["optional_values"]);
    }
    return $request;
  }

  function select_request($request, $fields = array()) {
    $virtual_fields = array("binet", "term", "requested_amount", "granted_amount", "used_amount", "state");
    if (!empty(array_intersect($virtual_fields, $fields))) {
      $fields = array_merge($fields, array("id", "wave", "sent"));
    }
    $id = $request;
    $request = select_entry(
      "request",
      array("id", "wave", "answer", "sent"),
      $request,
      $fields
    );
    if (!empty(array_intersect($virtual_fields, $fields))) {
      // TODO : check no request without subsidy
      $subsidies = select_subsidies(array("request" => $request["id"]));
      $subsidy = select_subsidy($subsidies[0]["id"], array("budget", "explanation"));
      $budget = select_budget($subsidy["budget"], array("binet", "term"));
      if (in_array("binet", $fields)) {
        $request["binet"] = $budget["binet"];
      }
      if (in_array("term", $fields)) {
        $request["term"] = $budget["term"];
      }
      if (in_array("requested_amount", $fields)) {
        $request["requested_amount"] = get_requested_amount_request($id);
      }
      if (in_array("granted_amount", $fields)) {
        $request["granted_amount"] = get_granted_amount_request($id);
      }
      if (in_array("used_amount", $fields)) {
        $request["used_amount"] = get_used_amount_request($id);
      }
      if (in_array("state", $fields)) {
        $wave = select_wave($request["wave"], array("state"));
        $request["state"] =
          $request["sent"] != 1 ?
            "rough_draft" :
            ($wave["state"] == "submission" ?
              (!isset($subsidy["explanation"]) ? "sent" : "reviewed") :
              ($request["granted_amount"] > 0 ? "accepted" : "rejected"));

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
      array("wave", "requested_amount", "granted_amount", "used_amount"),
      array(),
      array("binet", "term", "state"),
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

  function get_used_amount_request($request) {
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
