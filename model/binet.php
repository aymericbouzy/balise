<?php

  function create_binet($name, $admin) {
    $sql = "INSERT INTO binet(name)
            VALUES(':name')";
    $req = Database::get()->prepare($sql);
    $req->execute(array('name' => $name));
    $binet = $req->fetch(PDO::FETCH_ASSOC);

    $sql = "INSERT INTO binet_admin(binet, student, validated_by)
            VALUES(:binet, :admin, :validated_by)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet["id"], PDO::PARAM_INT);
    $req->bindParam(':admin', $admin, PDO::PARAM_INT);
    $req->bindParam(':validated_by', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    return $binet["id"];
  }

  function set_subsidy_provider($binet, $subsidy_steps) {
    $sql = "UPDATE binet
            SET subsidy_provider = 1, subsidy_steps = :subsidy_steps
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute(array(':subsidy_steps' => $subsidy_steps));
  }

  function get_admins($binet) {
    $sql = "SELECT *
            FROM student
            INNER JOIN binet_admin
            ON student.id = binet_admin.student
            WHERE binet_admin.binet = :binet AND binet_admin.validated_by != NULL";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function validate_admin_binet($student, $binet) {
    $sql = "UPDATE binet_admin
            SET validated_by = :validated_by
            WHERE binet_admin.binet = :binet AND binet.admin.student = :student
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $student, PDO::PARAM_INT);
    $req->bindParam(':validated_by', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
  }

  function request_admin_binet($binet) {
    $sql = "INSERT INTO binet_admin(binet, student)
            VALUES(:binet, :student)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
  }

  function get_status_admin_binet($binet) {
    $sql = "SELECT validated_by
            FROM binet_admin
            WHERE binet = :binet AND student = :student
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    if ($row = $req->fetch()) {
      return $row["validated_by"]) or -1;
    } else {
      return -2;
    }
  }

  function remove_admin_binet($binet, $student) {
    $sql = "DELETE
            FROM binet_admin
            WHERE binet = :binet AND student = :student
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $student, PDO::PARAM_INT);
    $req->execute();
  }

  function deactivate_binet($binet) {
    $sql = "UPDATE binet
            SET active = 0
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
  }

  function activate_binet($binet) {
    $sql = "UPDATE binet
            SET active = 1
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
  }
