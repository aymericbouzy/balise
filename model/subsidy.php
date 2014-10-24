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
    return select_entry(
      "subsidy",
      array("id", "budget", "request", "purpose", "requested_amount", "granted_amount", "explanation")
      $subsidy,
      $fields
    );
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
    $wave = select_wave($select_request($subsidy["request"])["wave"]);

    $sql = "SELECT SUM(operation_budget.amount) as real_amount
            FROM operation_budget
            INNER JOIN operation
            ON operation.id = operation_budget.operation
            WHERE operation_budget.budget = :budget AND operation.kes_validation_by != NULL AND operation.date <= :expiry_date";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':budget', $subsidy["budget"], PDO::PARAM_INT);
    $req->bindParam(':expiry_date', $wave["expiry_date"], PDO::PARAM_INT);
    $req->execute();
    $consumed_amount = $req->fetch(PDO::FETCH_ASSOC)["real_amount"];

    $subsidized_amount = get_subsidized_amount_budget($subsidy["budget"]);

    if ($consumed_amount >= $subsidized_amount) {
      return $subsidy["granted_amount"];
    } else {
      return $subsidy["granted_amount"] * $consumed_amount / $subsidized_amount;
    }
  }

  // TODO: selection by : consumed_amount
  function select_subsidies($criteria, $order_by = NULL, $ascending = true) {
    return select_entries("subsidy",
                          array("binet", "request", "requested_amount", "granted_amount"),
                          array(),
                          $criteria,
                          $order_by,
                          $ascending);
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
