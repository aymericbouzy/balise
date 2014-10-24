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
      create_subsidy($subsidy["budget"], $request, $subsidy["amount"], $subsidy["optionnal_values"]);
    }
    return $request;
  }

  function select_request($request, $fields = NULL) {
    $request = select_entry(
      "request",
      array("id", "wave", "answer"),
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

  function update_request($request, $hash) {
    update_entry("request",
                  array(),
                  array("answer"),
                  $request,
                  $hash);
  }

  function select_requests($criteria, $order_by = NULL, $ascending = true) {
    return select_entries("request",
                          array("wave"),
                          array(),
                          $criteria,
                          $order_by,
                          $ascending);
  }
