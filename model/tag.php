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

  function deactivate_tag($tag) {
    $sql = "UPDATE tag
            SET state = 0
            WHERE id = :tag
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':tag', $tag, PDO::PARAM_INT);
    $req->execute();
  }
