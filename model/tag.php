<?php

  function create_tag($binet, $name) {
    $sql = "INSERT INTO tag(state, binet, name)
            VALUES(1, :binet, :name)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute(array(
      ':name' => $name
    ));
    $tag = $req->fetch(PDO::FETCH_ASSOC);
    return $tag["id"];
  }

  function select_tag($tag, $fields = NULL) {
    return select_entry("tag", $tag, $fields);
  }

  function select_tags_binet($binet) {
    $sql = "SELECT *
            FROM tag
            WHERE binet = :binet";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
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
