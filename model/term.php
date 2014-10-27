<?php

  function select_term_binet($binet, $term, $fields = array()) {
    $term_binet = array();
    foreach ($fields as $field) {
      switch ($field) {
      case "balance":
        $term_binet[$field] = get_balance_term_binet($binet, $term);
        break;
      case "subsidized_amount_requested":
        $term_binet[$field] = get_subzidized_amount_requested_term_binet($binet, $term);
        break;
      case "subsidized_amount_granted":
        $term_binet[$field] = get_subzidized_amount_granted_term_binet($binet, $term);
        break;
      case "subsidized_amount_used":
        $term_binet[$field] = get_subzidized_amount_used_term_binet($binet, $term);
        break;
      case "spent_amount":
        $term_binet[$field] = get_spent_amount_term_binet($binet, $term);
        break;
      case "earned_amount":
        $term_binet[$field] = get_earned_amount_term_binet($binet, $term);
        break;
      }
    }
    return $term_binet;
  }

  function select_terms($criteria = array(), $order_by = NULL, $ascending = true) {
    $terms = select_entries(
      "binet_admin",
      array("binet", "term"),
      array(),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
    return filter_entries(
      $terms,
      "term_binet",
      array("balance", "subsidized_amount_requested", "subsidized_amount_granted", "subsidized_amount_used", "spent_amount", "earned_amount"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function get_balance_term_binet($binet, $term) {
    $balance = 0;
    foreach (select_budgets(array("binet" => $binet, "term" => $term)) as $budget) {
      $real_amount = get_real_amount_budget($budget["id"]);
      $balance += $real_amount;
      $balance += get_subsidized_amount_used_budget($budget["id"]);
    }
    return $balance;
  }

  function get_subsidized_amount_requested_term_binet($binet, $term) {
    $amount = 0;
    foreach (select_requests(array("binet" => $binet, "term" => $term)) as $request) {
      $amount += get_requested_amount_request($request["id"]);
    }
  }

  function get_subsidized_amount_granted_term_binet($binet, $term) {
    $amount = 0;
    foreach (select_requests(array("binet" => $binet, "term" => $term)) as $request) {
      $amount += get_granted_amount_request($request["id"]);
    }
  }

  function get_subsidized_amount_used_term_binet($binet, $term) {
    $amount = 0;
    foreach (select_requests(array("binet" => $binet, "term" => $term)) as $request) {
      $amount += get_used_amount_request($request["id"]);
    }
  }

  function get_spent_amount_term_binet($binet, $term) {
    $amount = 0;
    foreach (select_operations(array("binet" => $binet, "term" => $term, "amount" => array("<", 0))) as $operation) {
      $amount += select_operation($operation["id"], array("amount"))["amount"];
    }
    return $amount;
  }

  function get_earned_amount_term_binet($binet, $term) {
    $amount = 0;
    foreach (select_operations(array("binet" => $binet, "term" => $term, "amount" => array(">", 0))) as $operation) {
      $amount += select_operation($operation["id"], array("amount"))["amount"];
    }
    return $amount;
  }