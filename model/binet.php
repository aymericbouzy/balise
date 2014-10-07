<?php

  function create_binet($name, $admin) {
    $req = Database::get()->prepare("INSERT INTO binet(name) VALUES(':name')");
    $req->execute(array('name' => $name));
    $binet = $req->fetch(PDO::FETCH_ASSOC);
    $req = Database::get()->prepare("INSERT INTO binet_admin(binet, student) VALUES(:binet, :admin)");
    $req->bindParam(':binet', $binet["id"], PDO::PARAM_INT);
    $req->bindParam(':admin', $admin, PDO::PARAM_INT);
    $req->execute();
  }

  function set_subsidy_provider($binet, $subsidy_steps) {
    $req = Database::get()->prepare("UPDATE binet SET subsidy_provider = 1, subsidy_steps = :subsidy_steps WHERE id = :binet LIMIT 1");
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute(array(':subsidy_steps' => $subsidy_steps));
  }

  
