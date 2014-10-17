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
    $sql = "SELECT DISTINCT id
            FROM tag
            INNER JOIN budget_tag
            ON budget_tag.tag = tag.id
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
    $sql = "SELECT *
            FROM spending_tag
            INNER JOIN tag
            ON tag.id = spending_tag.tag
            WHERE spending_tag.spending = :spending";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_subsidy($subsidy) {
    $sql = "SELECT *
            FROM subsidy_tag
            INNER JOIN tag
            ON tag.id = subsidy_tag.tag
            WHERE subsidy_tag.subsidy = :subsidy";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_income($income) {
    $sql = "SELECT *
            FROM income_tag
            INNER JOIN tag
            ON tag.id = income_tag.tag
            WHERE income_tag.income = :income";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':income', $income, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }
