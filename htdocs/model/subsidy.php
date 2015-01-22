<?php

  function create_subsidy($budget, $request, $requested_amount, $optional_values = array()) {
    $values["budget"] = $budget;
    $values["request"] = $request;
    $values["requested_amount"] = $requested_amount;
    return create_entry(
      "subsidy",
      array("budget", "request", "requested_amount"),
      array("purpose"),
      array_merge($values, $optional_values)
    );
  }

  function select_subsidy($subsidy, $fields = NULL) {
    $subsidy = select_entry(
      "subsidy",
      array("id", "budget", "request", "purpose", "requested_amount", "granted_amount", "explanation"),
      $subsidy,
      $fields
    );
    foreach ($fields as $field) {
      switch ($field) {
      case "used_amount":
        $subsidy[$field] = get_used_amount_subsidy($subsidy["id"]);
        break;
      }
    }
    return $subsidy;
  }

  function exists_subsidy($subsidy) {
    return select_subsidy($subsidy) ? true : false;
  }

  function update_subsidy($subsidy, $hash) {
    update_entry("subsidy",
                  array("requested_amount", "granted_amount"),
                  array("purpose", "explanation"),
                  $subsidy,
                  $hash);
  }

  function select_subsidies($criteria, $order_by = NULL, $ascending = true) {
    return select_entries(
      "subsidy",
      array("binet", "request", "requested_amount", "granted_amount"),
      array(),
      array("used_amount"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function get_subsidized_amount($criteria) {
    return select_request(
      "SUM(granted_amount) as subsidized_amount",
      "subsidy",
      array("budget", "requested_amount", "granted_amount", "request"),
      array(),
      $criteria
    )[0]["subsidized_amount"];
  }

  function get_used_amount_subsidy($subsidy) {
    $budget = select_subsidy($subsidy, array("budget"))["budget"];
    foreach(get_subsidized_amount_used_details_budget($budget) as $budget_subsidy) {
      if ($budget_subsidy["id"] == $subsidy) {
        $amount = $budget_subsidy["used_amount"];
      }
    }
    return $amount;
  }
