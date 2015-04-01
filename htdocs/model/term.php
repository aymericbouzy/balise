<?php

  function select_term_binet($term_binet, $fields = array()) {
    if (exists_term_binet($term_binet)) {
      return select_term_binet_direct($term_binet, $fields);
    } else {
      return false;
    }
  }

  function select_term_binet_direct($term_binet, $fields = array()) {
    $id = explode("/", $term_binet);
    $binet = $id[0];
    $term = $id[1];
    $term_binet = array();
    $term_binet["id"] = term_id($id[0], $id[1]);
    $term_binet["binet"] = $id[0];
    $term_binet["term"] = $id[1];
    $present_virtual_fields = array_intersect($fields, array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state", "amount_requested_in_sent", "amount_requested_in_rough_drafts", "amount_requested_in_published"));
    if (!is_empty($present_virtual_fields)) {
      if (in_array("state", $fields)) {
        $fields = array_merge($fields, array("real_balance", "expected_balance"));
      }
      $budgets = array();
      foreach (select_budgets(array("binet" => $term_binet["binet"], "term" => $term_binet["term"])) as $budget) {
        $budgets[] = select_budget($budget["id"], array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "subsidized_amount_available", "amount", "subsidized_amount"));
      }
      $operations = array();
      foreach (select_operations(array("binet" => $term_binet["binet"], "term" => $term_binet["term"])) as $operation) {
        $operations[] = select_operation($operation["id"], array("amount"));
      }
      if (in_array("subsidized_amount_used", $fields) || in_array("real_balance", $fields)) {
        $term_binet["subsidized_amount_used"] = sum_array($budgets, "subsidized_amount_used");
      }
      if (in_array("subsidized_amount_granted", $fields)) {
        $term_binet["subsidized_amount_granted"] = sum_array($budgets, "subsidized_amount_granted");
      }
      if (in_array("subsidized_amount_requested", $fields)) {
        $term_binet["subsidized_amount_requested"] = sum_array($budgets, "subsidized_amount_requested");
      }
      if (in_array("subsidized_amount_available", $fields)) {
        $term_binet["subsidized_amount_available"] = sum_array($budgets, "subsidized_amount_available");
      }
      if (in_array("real_spending", $fields)) {
        $term_binet["real_spending"] = sum_array($operations, "amount", "negative");
      }
      if (in_array("real_income", $fields)) {
        $term_binet["real_income"] = sum_array($operations, "amount", "positive");
      }
      if (in_array("real_balance", $fields)) {
        $term_binet["real_balance"] = sum_array($operations, "amount") + $term_binet["subsidized_amount_used"];
      }
      if (in_array("expected_spending", $fields)) {
        $term_binet["expected_spending"] = sum_array($budgets, "amount", "negative");
      }
      if (in_array("expected_income", $fields)) {
        $term_binet["expected_income"] = sum_array($budgets, "amount", "positive");
      }
      if (in_array("expected_balance", $fields)) {
        $term_binet["expected_balance"] = sum_array($budgets, "amount") + sum_array($budgets, "subsidized_amount");
      }
      if (in_array("state", $fields)) {
        $term_binet["state"] = $term_binet["real_balance"] >= 0 ? "green" : ($term_binet["expected_balance"] >= 0 ? "orange" : "red");
      }
      if (in_array("amount_requested_in_sent", $fields)) {
        $sum = 0;
        foreach (select_requests(array("sending_date" => array("IS", "NOT NULL"), "binet" => $term_binet["binet"], "term" => $term_binet["term"])) as $request) {
          $sum += get_requested_amount_request($request["id"]);
        }
        $term_binet["amount_requested_in_sent"] = $sum;
      }
      if (in_array("amount_requested_in_rough_drafts", $fields)) {
        $sum = 0;
        foreach (select_requests(array("sending_date" => array("IS", "NULL"), "binet" => $term_binet["binet"], "term" => $term_binet["term"])) as $request) {
          $sum += get_requested_amount_request($request["id"]);
        }
        $term_binet["amount_requested_in_rough_drafts"] = $sum;
      }
      if (in_array("amount_requested_in_published", $fields)) {
        $sum = 0;
        foreach (select_requests(array("state" => array("IN", array("accepted", "rejected")), "binet" => $term_binet["binet"], "term" => $term_binet["term"])) as $request) {
          $sum += get_requested_amount_request($request["id"]);
        }
        $term_binet["amount_requested_in_published"] = $sum;
      }
    }
    return $term_binet;
  }

  function exists_term_binet($term_binet) {
    $id = explode("/", $term_binet);
    $binet = $id[0];
    $term = $id[1];
    $terms = select_terms(array("binet" => $binet, "term" => $term));
    $operations = select_operations(array("binet" => $binet, "term" => $term));
    $budgets = select_budgets(array("binet" => $binet, "term" => $term));
    return current_term($binet) == $term || !(is_empty($terms) && is_empty($budgets) && is_empty($operations));
  }


  function select_terms($criteria = array(), $order_by = "term", $ascending = true) {
    $terms = select_with_request_string(
      "CONCAT(binet, '/', term) as id",
      "binet_member",
      array("binet", "term", "student", "rights"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
    return filter_entries(
      $terms,
      "term_binet_direct",
      array("balance", "subsidized_amount_requested", "subsidized_amount_granted", "subsidized_amount_used", "spent_amount", "earned_amount", "amount_requested_in_published", "amount_requested_in_rough_drafts", "amount_requested_in_sent"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function term_id($binet, $year) {
    return $binet."/".$year;
  }

  // useless : to delete

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
