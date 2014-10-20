<?php

    function create_budget($binet, $amount, $label, $term) {
      $binet = select_binet($binet);
      $sql = "INSERT INTO wave(binet, amount, term, label)
              VALUES(:binet, :amount, :term, :label)";
      $req = Database::get()->prepare($sql);
      $req->bindParam(':binet', $binet["id"], PDO::PARAM_INT);
      $req->bindParam(':amount', $amount, PDO::PARAM_INT);
      $req->bindParam(':term', $term ? $term : $binet["term"], PDO::PARAM_INT);
      $req->execute(array(
        ':label' => $label
      ));
      $budget = $req->fetch(PDO::FETCH_ASSOC);
      return $budget["id"];
    }

    function select_budget($budget, $fields = NULL) {
      return select_entry("budget", $budget, $fields);
    }

    function select_budgets($criteria, $order_by = NULL, $ascending = true) {
      return select_entries("budget",
                            array("binet", "amount", "term"),
                            array(),
                            $criteria,
                            $order_by,
                            $ascending);
    }

    function update_budget($budget, $hash) {
      update_entry("budget",
                    array("amount"),
                    array("label"),
                    $budget,
                    $hash);
    }

    function get_real_amount_budget($budget) {
      $sql = "SELECT SUM(spending_budget.amount) as real_amount
              FROM spending_budget
              INNER JOIN spending
              ON spending.id = spending_budget.spending
              WHERE spending_budget.budget = :budget AND spending.kes_validation_by != NULL";
      $req = Database::get()->prepare($sql);
      $req->bindParam(':budget', $budget, PDO::PARAM_INT);
      $req->execute();
      return $req->fetch(PDO::FETCH_ASSOC)["real_amount"];
    }

    function get_subsidized_amount_budget($budget) {
      $sql = "SELECT SUM(subsidy.granted_amount) as subsidized_amount
              FROM subsidy
              INNER JOIN wave
              ON wave.id = subsidy.wave
              WHERE wave.published = 1 AND subsidy.budget = :budget";
      $req = Database::get()->prepare($sql);
      $req->bindParam(':budget', $budget, PDO::PARAM_INT);
      $req->execute();
      return $req->fetch(PDO::FETCH_ASSOC)["subsidized_amount"];
    }
