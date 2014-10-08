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

  function select_tag($tag) {
    $sql = "SELECT *
            FROM tag
            WHERE id = :tag
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
  }

  function deactivate_tag($tag) {
    $sql = "UPDATE tag
            SET state = 0
            WHERE id = :tag
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
  }

  function reactivate_tag($tag) {
    $sql = "UPDATE tag
            SET state = 1
            WHERE id = :tag
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
  }

  function select_active_tags_binet($binet) {
    $sql = "SELECT *
            FROM tag
            WHERE binet = :binet AND state = 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
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

  function add_tag_spending($tag, $spending) {
    $sql = "INSERT INTO spending_tag(spending, tag)
            VALUES(:spending, :tag)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
  }

  function remove_tag_spending($tag, $spending) {
    $sql = "DELETE
            FROM spending_tag
            WHERE tag = :tag AND spending = :spending";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':spending', $spending, PDO::PARAM_INT);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
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

  function add_tag_subsidy($tag, $subsidy) {
    $sql = "INSERT INTO subsidy_tag(subsidy, tag)
            VALUES(:subsidy, :tag)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->execute();
  }

  function remove_tag_subsidy($tag, $subsidy) {
    $sql = "DELETE
            FROM subsidy_tag
            WHERE tag = :tag AND subsidy = :subsidy";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':subsidy', $subsidy, PDO::PARAM_INT);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
  }

  function select_tags_subsdiy($subsidy) {
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
