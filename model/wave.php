<?php

  function create_wave ($binet, $submission_date, $expiry_date) {
    $sql = "INSERT INTO wave(binet, submission_date, expiry_date)
            VALUES(:binet, :submission_date, :expiry_date)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute(array(
      ':purpose' => $purpose,
      ':submission_date' => $submission_date,
      ':expiry_date' => $expiry_date
    ));
    $wave = $req->fetch(PDO::FETCH_ASSOC);
    return $wave["id"];
  }
