<?php

  function create_wave($binet, $submission_date, $expiry_date) {
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

  function select_wave($wave) {
    $sql = "SELECT *
            FROM wave
            WHERE id = :wave
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':wave', $wave, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
  }

  function select_waves($criteria) {
    return select_entries("wave",
                          array("binet", "published"),
                          array("submission_date", "expiry_date"),
                          $criteria);
  }

  function publish_wave($wave) {
    $sql = "UPDATE wave
            SET published = 1
            WHERE id = :wave
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':wave', $wave, PDO::PARAM_INT);
    $req->execute();
  }
