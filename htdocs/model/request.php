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
      if (in_array("available_amount", $fields)) {
        $request["available_amount"] = get_available_amount_request($id);
      }
      if (in_array("state", $fields)) {
        $wave = select_wave($request["wave"], array("state", "published"));
        $request["state"] =
          is_empty($request["sending_date"]) ?
            ($wave["state"] == "submission" ?
              "rough_draft" :
              ($wave["state"] == "deliberation" ? "late_rough_draft" : "overdue_rough_draft")) :
            (!$request["reviewed"] ?
              ($request["sending_date"] > $wave["submission_date"] ? "sent_late" : "sent") :
              (($request["granted_amount"]) > 0 ?
                ($wave["published"] ? "accepted" : "reviewed_accepted") :
                ($wave["published"] ? "rejected" : "reviewed_rejected")));
      }
    }

    return $request;
  }

  function exists_request($request) {
    return select_request($request) ? true : false;
  }

  function update_request($request, $hash) {
    update_entry(
      "request",
      array(),
      array("answer"),
      $request,
      $hash
    );
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
    update_entry(
      "request",
      array("sending_date"),
      array(),
      $request,
      array("sending_date" => current_date())
    );
  }

  function send_back_request($request) {
    $sql = "UPDATE request
            SET sending_date = NULL
            WHERE id = :request
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':request', $request, PDO::PARAM_INT);
    $req->execute();
  }

  function review_request($request) {
    update_entry(
      "request",
      array("reviewed"),
      array(),
      $request,
      array("reviewed" => 1)
    );
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

  function get_available_amount_request($request) {
    $amount = 0;
    foreach (select_subsidies(array("request" => $request)) as $subsidy) {
      $amount += select_subsidy($subsidy["id"], array("available_amount"))["available_amount"];
    }
    return $amount;
  }

  function select_operations_request($request) {
    $operations = array();
    foreach (select_subsidies(array("request" => $request)) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("budget"));
      $operations = array_merge($operations, array_keys(ids_as_keys(select_operations_budget($subsidy["budget"]))));
    }
    return array_unique($operations);
  }
