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
    $present_virtual_fields = array_intersect($virtual_fields, $fields);
    if (!is_empty($present_virtual_fields)) {
      $fields = array_merge($fields, array("id", "wave", "sending_date", "granted_amount", "reviewed"));
    }
    $id = $request;
    $request = select_entry(
      "request",
      array("id", "wave", "answer", "sending_date", "reviewed"),
      $request,
      $fields
    );
    if (!is_empty($present_virtual_fields)) {
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
          is_empty($request["sending_date"]) ?
            "rough_draft" :
            (in_array($wave["state"], array("deliberation", "submission")) ?
              ($request["reviewed"] != 1 ? "sent" : ($request["granted_amount"] > 0 ? "reviewed_accepted" : "reviewed_rejected")) :
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
    if (!isset($criteria["sending_date"]) && !isset($criteria["state"])) {
      $criteria["sending_date"] = array("IS", "NOT NULL");
    }
    return select_entries(
      "request",
      array("wave", "sending_date", "reviewed"),
      array(),
      array("binet", "term", "requested_amount", "granted_amount", "used_amount", "state"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function delete_request($request) {
    foreach (select_subsidies(array("request" => $request)) as $subsidy) {
      delete_subsidy($subsidy["id"]);
    }
    delete_entry("request", $request);
  }

  function send_request($request) {
    $sql = "UPDATE request
            SET sending_date = CURRENT_DATE()
            WHERE id = :request
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':request', $request, PDO::PARAM_INT);
    $req->execute();
  }

  function review_request($request) {
    $sql = "UPDATE request
            SET reviewed = 1
            WHERE id = :request
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':request', $request, PDO::PARAM_INT);
    $req->execute();
  }

  function get_used_amount_request($request) {
    $amount = 0;
    foreach(select_subsidies(array("request" => $request)) as $subsidy) {
      $amount += get_used_amount_subsidy($subsidy["id"]);
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
