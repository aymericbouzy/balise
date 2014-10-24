<?php

  function create_tag($name) {
    $values["name"] = $name;
    $values["clean_name"] = clean_string($name);
    return create_entry(
      "tag",
      array(),
      array("name", "clean_name"),
      $values
    );
  }

  function select_tag($tag, $fields = NULL) {
    return select_entry(
      "tag",
      array("id", "name", "clean_name"),
      $tag,
      $fields
    );
  }

  // TODO: selecion by : number of times used, order_by(number_of_times_used),
  function select_tags($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries("tag",
                          array(),
                          array("name", "clean_name"),
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

  function select_tags_operation($operation) {
    $sql = "SELECT budget_tag.tag
            FROM budget_tag
            INNER JOIN operation_budget
            ON operation_budget.budget = budget_tag.budget
            WHERE operation_budget.operation = :operation";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':operation', $operation, PDO::PARAM_INT);
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
