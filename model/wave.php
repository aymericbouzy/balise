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
    $sql = "SELECT *
            FROM wave
            WHERE true";
    foreach ($criteria as $column => $value) {
      if (in_array($column, array("submission_date", "expiry_date"))) {
        $sql .= " AND ".$column." > :".$column;
      }
      if (in_array($column, array("binet", "published"))) {
        $sql .= " AND ".$column." = :".$column;
      }
    }
    $req = Database::get()->prepare($sql);
    foreach ($criteria as $column => $value) {
      if (in_array($column, array("submission_date", "expiry_date"))) {
        $req->bindParam(':'.$column, $value, PDO::PARAM_STR);
      }
      if (in_array($column, array("binet", "published"))) {
        $req->bindParam(':'.$column, $value, PDO::PARAM_INT);
      }
    }
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
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
