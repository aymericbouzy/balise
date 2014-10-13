<?php

  /*
  Creates a new binet with a least one name.

  @param string $name name of the binet, varchar(50) NOT NULL in table 'binet' and 'binet_admin'
  @param int $admin id of the student as admin of the binet, int(11) DEFAULT NULL in table 'binet_admin'

  @return int Id of the binet

  @uses $_SESSION["student"] to fill `validated_by` int(11) DEFAULT NULL in table 'binet' for insert

  */
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

  function select_binet($binet) {
    $sql = "SELECT *
            FROM binet
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
  }

  /*
  @param int $binet sets binet to be subsidy_provider if not 0 , tinyint(1) NOT NULL DEFAULT '0' in table 'binet'
  @param string $subsidy_steps text information about how to use/get subsidy, text in table 'binet'
  */
  function set_subsidy_provider($binet, $subsidy_steps) {
    $sql = "UPDATE binet
            SET subsidy_provider = 1, subsidy_steps = :subsidy_steps
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute(array(':subsidy_steps' => $subsidy_steps));
  }

  /*
  @param int $binet id of the binet , int(11) NOT NULL in table 'binet'

  @return array Array containing all admins of current binet
  */
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

  /*
  @param int $student id of the student to be set as admin, int(11) NOT NULL in table 'binet_admin'
  @param int $binet id of the binet , int(11) NOT NULL in table 'binet_admin'

  @uses $_SESSION["student"] to fill `validated_by` int(11) DEFAULT NULL in table 'binet_admin' for update
  */
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

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'

    @uses $_SESSION["student"] to fill `student` int(11) DEFAULT NULL in table 'binet_admin' for insert
  */
  function request_admin_binet($binet) {
    $sql = "INSERT INTO binet_admin(binet, student)
            VALUES(:binet, :student)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'

    @uses $_SESSION["student"] to fill `student` int(11) DEFAULT NULL in table 'binet' for select
  */
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

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'
    @param int $student if of the student, int(11) NOT NULL in table 'binet_admin'
  */
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
  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet'
  */
  function deactivate_binet($binet) {
    $sql = "UPDATE binet
            SET active = 0
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet'
  */
  function activate_binet($binet) {
    $sql = "UPDATE binet
            SET active = 1
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
  }
