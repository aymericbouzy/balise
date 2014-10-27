<?php

  function select_term_binet($binet, $term, $fields = array()) {
    $term_binet = array();
    foreach ($fields as $field) {
      switch ($field) {
      case "balance":
        $term_binet[$field] = get_balance_term_binet($binet, $term);
        break;
      }
    }
    return $term_binet;
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

  function get_subzidized_amount_requested_term_binet($binet, $term) {
    $amount = 0;
    foreach (select_requests(array("binet" => $binet, "term" => $term)) as $request) {
      $amount += get_requested_amount_request($request["id"]);
    }
  }
