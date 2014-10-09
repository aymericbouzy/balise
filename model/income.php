<?php

  function create_income($amount, $binet, $type, $comment) {
    $sql = "INSERT INTO income(date, amount, type, created_by, comment)
            VALUES(CURDATE(), :amount, :binet, :type, :student, :comment)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':amount', $amount, PDO::PARAM_INT);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':type', $type, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute(array(
      ':comment' => $comment
    ));
    $income = $req->fetch(PDO::FETCH_ASSOC);
    return $income["id"];
  }

  
