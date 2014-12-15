<?php

  /*
  Creates a new binet with at least one name.

  @param string $name name of the binet, varchar(50) NOT NULL in table 'binet' and 'binet_admin'
  @param int $admin id of the student as admin of the binet, int(11) DEFAULT NULL in table 'binet_admin'

  @return int Id of the binet

  @uses $_SESSION["student"] to fill `validated_by` int(11) DEFAULT NULL in table 'binet' for insert

  */
  function create_binet($name, $term) {
    $values["name"] = $name;
    $values["current_term"] = $term;
    $values["clean_name"] = clean_string($values["name"]);
    $values["description"] = "";
    return create_entry(
      "binet",
      array("current_term"),
      array("name", "clean_name", "description"),
      $values
    );
  }

  function select_binet($binet, $fields = array()) {
    $binet = select_entry(
      "binet",
      array("id", "name", "clean_name", "description", "current_term", "subsidy_provider", "subsidy_steps"),
      $binet,
      $fields
    );
    return $binet;
  }

  function select_binets($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries(
      "binet",
      array("subsidy_provider", "current_term"),
      array("name", "clean_name"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function update_binet($binet, $hash) {
    if (isset($hash["name"])) {
      $hash["clean_name"] = clean_string($values["name"]);
    }
    update_entry("binet",
                  array("name", "clean_name"),
                  array("description"),
                  $binet,
                  $hash);
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
  function select_admins($binet, $term) {
    $sql = "SELECT student AS id
            FROM binet_admin
            WHERE binet = :binet AND term = :term";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function binet_admins_current_student() {
    $sql = "SELECT binet, term
            FROM binet_admin
            WHERE student = :student";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'

    @uses $_SESSION["student"] to fill `student` int(11) DEFAULT NULL in table 'binet_admin' for insert
  */
  function add_admin_binet($student, $binet, $term) {
    $sql = "INSERT INTO binet_admin(student, binet, term)
            VALUES(:student, :binet, :term)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $student, PDO::PARAM_INT);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
    $req->execute();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'

    @uses $_SESSION["student"] to fill `student` int(11) DEFAULT NULL in table 'binet' for select
  */

  // useless for the time being
  function status_admin_binet($binet, $term = NULL) {
    $sql = "SELECT *
            FROM binet_admin
            WHERE binet = :binet ".(empty($term) ? "" : "AND term = :term ")."AND student = :student
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    if (!empty($term)) {
      $req->bindParam(':term', $term, PDO::PARAM_INT);
    }
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    return !empty($req->fetchAll());
  }

  function status_admin_current_binet($binet) {
    $sql = "SELECT *
    FROM binet_admin
    INNER JOIN binet
    ON binet_admin.binet = binet.id AND binet_admin.term = binet.current_term
    WHERE binet_admin.binet = :binet AND binet_admin.student = :student
    LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    return !empty($req->fetchAll());
  }

  function current_term($binet) {
    return select_binet($binet, array("current_term"))["current_term"];
  }
  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'
    @param int $student if of the student, int(11) NOT NULL in table 'binet_admin'
  */
  function remove_admin_binet($binet, $term, $student) {
    $sql = "DELETE
            FROM binet_admin
            WHERE binet = :binet AND term = :term AND student = :student
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
    $req->bindParam(':student', $student, PDO::PARAM_INT);
    $req->execute();
  }
  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet'
  */
  function deactivate_binet($binet) {
    $sql = "UPDATE binet
            SET current_term = NULL
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet'
  */
  function change_term_binet($binet, $term) {
    $sql = "UPDATE binet
            SET current_term = :term
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
    $req->execute();
  }
