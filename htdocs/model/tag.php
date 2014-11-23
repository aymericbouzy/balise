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

  function add_tag_budget($tag, $budget) {
    $sql = "INSERT INTO budget_tag(budget, tag)
            VALUES(:budget, :tag)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':budget', $budget, PDO::PARAM_INT);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
  }

  function remove_tags_budget($budget) {
    $sql = "DELETE
            FROM budget_tag
            WHERE budget = :budget";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
  }

  function select_tag($tag, $fields = NULL) {
    $tag = select_entry(
      "tag",
      array("id", "name", "clean_name"),
      $tag,
      $fields
    );
    foreach ($fields as $field) {
      switch ($field) {
      case "occurrences":
        $tag[$field] = get_occurrences_tag($tag["id"]);
        break;
      }
    }
    return $tag;
  }

  function select_tags($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries(
      "tag",
      array(),
      array("name", "clean_name"),
      array("occurences"),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function select_tags_binet($binet, $term = NULL) {
    $sql = "SELECT budget_tag.tag, COUNT(budget_tag.tag) as frequency
            FROM budget_tag
            INNER JOIN budget
            ON budget.id = budget_tag.budget
            WHERE budget.binet = :binet
            GROUP BY budget_tag.tag";
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
    $sql = "SELECT budget_tag.tag, COUNT(budget_tag.tag) as frequency
            FROM budget_tag
            INNER JOIN operation_budget
            ON operation_budget.budget = budget_tag.budget
            WHERE operation_budget.operation = :operation
            GROUP BY budget_tag.tag";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':operation', $operation, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_subsidy($subsidy) {
    $sql = "SELECT budget_tag.tag, COUNT(budget_tag.tag) as frequency
            FROM budget_tag
            INNER JOIN subsidy
            ON subsidy.budget = budget_tag.budget
            WHERE subsidy.id = :subsidy
            GROUP BY budget_tag.tag";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_request($request) {
    $sql = "SELECT budget_tag.tag, COUNT(budget_tag.tag) as frequency
            FROM budget_tag
            INNER JOIN subsidy
            ON subsidy.budget = budget_tag.budget
            WHERE subsidy.request = :request
            GROUP BY budget_tag.tag";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':request', $request, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_wave($wave) {
    $sql = "SELECT budget_tag.tag, COUNT(budget_tag.tag) as frequency
            FROM budget_tag
            INNER JOIN subsidy
            ON subsidy.budget = budget_tag.budget
            INNER JOIN request
            ON subsidy.request = request.id
            WHERE request.wave = :wave
            GROUP BY budget_tag.tag";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':request', $request, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_tags_budget($budget) {
    $sql = "SELECT tag
            FROM budget_tag
            WHERE budget = :budget";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':budget', $budget, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function get_occurrences_tag($tag) {
    $sql = "SELECT COUNT(1) as occurrences
            FROM budget_tag
            WHERE budget_tag.tag = :tag";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch()["occurrences"];
  }
