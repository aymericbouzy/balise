<?php

  function create_binet($name, $admin) {
    $req = Database::get()->prepare("INSERT INTO binet(name) VALUES(':name')");
    $binet = $req->execute(array('name' => $name));
    $req = Database::get()->prepare("INSERT INTO binet_admin(binet, student) VALUES(:binet, :admin)");
    $req->bindParam(':binet', $binet["id"], PDO::PARAM_INT);
    $req->bindParam(':admin', $admin, PDO::PARAM_INT);
    $req->execute();
  }
