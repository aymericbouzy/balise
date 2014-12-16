<?php
  include "global/initialisation.php";

  $sql = "SELECT id FROM budget WHERE true AND binet = :binet AND term = :term";
  $req = Database::get()->prepare($sql);
  $column = "binet";
  $real_value = "1";
  $req->bindValue(':'.$column, $real_value, PDO::PARAM_INT);
  $column = "term";
  $real_value = "2012";
  $req->bindValue(':'.$column, $real_value, PDO::PARAM_INT);
  $req->execute();
  var_dump($req->errorInfo());
