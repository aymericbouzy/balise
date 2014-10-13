<?php

    function create_budget($binet, $amount, $label, $term = NULL) {
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

    function select_budget($budget) {
      $sql = "SELECT *
              FROM budget
              WHERE id = :budget
              LIMIT 1";
      $req = Database::get()->prepare($sql);
      $req->bindParam(':budget', $budget, PDO::PARAM_INT);
      $req->execute();
      return $req->fetch(PDO::FETCH_ASSOC);
    }

    function select_budgets($criteria) {
      return select_entries("budget",
                            array("binet", "amount", "term"),
                            array(),
                            $criteria);
    }
