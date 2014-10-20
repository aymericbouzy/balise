<?php

  function create_subsidy($budget, $wave, $amount, $optional_values = array()) {
    $values["budget"] = $budget;
    $values["wave"] = $wave;
    $values["amount"] = $amount;
    $values["created_by"] = $_SESSION["student"];
    return create_entry(
      "subsidy",
      array("budget", "wave", "amount", "created_by"),
      array("purpose"),
      array_merge($values, $optional_values)
    );
  }

  function select_subsidy($subsidy, $fields = NULL) {
    return select_entry("subsidy", $subsidy, $fields);
  }

  function update_subsidy($subsidy, $hash) {
    update_entry("subsidy",
                  array("requested_amount", "granted_amount"),
                  array("purpose", "explanation"),
                  $subsidy,
                  $hash);
  }

  function get_consumed_amount_subsidy($subsidy) {
    $subsidy = select_subsidy($subsidy);
    $consumed_amount = get_real_amount_budget($subsidy["budget"]);
    $subsidized_amount = get_subsidized_amount_budget($subsidy["budget"]);
    if ($consumed_amount >= $subsidized_amount) {
      return $subsidy["granted_amount"];
    } else {
      return $subsidy["granted_amount"] * $consumed_amount / $subsidized_amount;
    }
  }

  function select_subsidies($criteria, $order_by = NULL, $ascending = true) {
    return select_entries("subsidy",
                          array("binet", "wave", "requested_amount", "granted_amount", "created_by"),
                          array(),
                          $criteria,
                          $order_by,
                          $ascending);
  }
