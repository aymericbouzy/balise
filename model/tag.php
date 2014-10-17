<?php

  function create_tag($name) {
    $sql = "INSERT INTO tag(name)
            VALUES(:name)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':name', $name, PDO::PARAM_STR);
    $req->execute();
    $tag = $req->fetch(PDO::FETCH_ASSOC);
    return $tag["id"];
  }

  function select_tag($tag, $fields = NULL) {
    return select_entry("tag", $tag, $fields);
  }

  function select_tags($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries("tag",
                          array(),
                          array("name"),
                          $criteria,
                          $order_by,
                          $ascending);
  }

  function select_tags_binet($binet, $term = NULL) {
    $sql = "SELECT DISTINCT budget_tag.tag
            FROM budget_tag
            INNER JOIN budget
            ON budget.id = budget_tag.budget
            WHERE budget.binet = :binet";
    if ($term) {
      $sql .= " AND budget.term = :term";
    }
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    if ($term) {
      $req->bindParam(':term', $term, PDO::PARAM_INT);
    }
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_spending($spending) {
    $sql = "SELECT budget_tag.tag
            FROM budget_tag
            INNER JOIN spending_budget
            ON spending_budget.budget = budget_tag.budget
            WHERE spending_budget.spending = :spending";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_subsidy($subsidy) {
    $sql = "SELECT budget_tag.tag
            FROM budget_tag
            INNER JOIN subsidy
            ON subsidy.budget = budget_tag.budget
            WHERE subsidy.id = :subsidy";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_income($income) {
    $sql = "SELECT budget_tag.tag
            FROM budget_tag
            INNER JOIN income_budget
            ON income_budget.budget = budget_tag.budget
            WHERE income_budget.income = :income";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':income', $income, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }
