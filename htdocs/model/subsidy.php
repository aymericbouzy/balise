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
    $present_virtual_fields = array_intersect($fields, array("available_amount"));
    if (!is_empty($present_virtual_fields)) {
      $fields = array_merge(array("expiry_date", "granted_amount", "used_amount", "published"), $fields);
    }
    $present_virtual_fields = array_intersect($fields, array("used_amount", "wave", "expiry_date", "published"));
    if (!is_empty($present_virtual_fields)) {
      $fields = array_merge(array("id", "request", "wave", "budget"), $fields);
    }
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
      case "wave":
        $subsidy[$field] = select_request($subsidy["request"], array("wave"))["wave"];
        break;
      }
    }
    if (in_array("expiry_date", $fields) || in_array("published", $fields)) {
      $wave = select_wave($subsidy["wave"], array("expiry_date", "published"));
      $subsidy["expiry_date"] = $wave["expiry_date"];
      $subsidy["published"] = $wave["published"];
    }
    if (in_array("granted_amount", $fields)) {
      set_if_not_set($subsidy["granted_amount"], 0);
    }
    if (in_array("available_amount", $fields)) {
      $subsidy["available_amount"] = current_date() > $subsidy["expiry_date"] || !$subsidy["published"] ? 0 : $subsidy["granted_amount"] - $subsidy["used_amount"];
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

  function delete_subsidy($subsidy) {
    delete_entry("subsidy", $subsidy);
  }

  function select_subsidies($criteria, $order_by = "", $ascending = true) {
    return select_entries(
      "subsidy",
      array("budget", "request", "requested_amount", "granted_amount"),
      array(),
      array("used_amount", "wave", "expiry_date"),
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
    foreach (get_subsidized_amount_used_details_budget($budget) as $budget_subsidy) {
      if ($budget_subsidy["id"] == $subsidy) {
        $amount = $budget_subsidy["used_amount"];
      }
    }
    return $amount;
  }
