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

  function exists_binet($binet) {
    return select_binet($binet) ? true : false;
  }

  function select_binets($criteria = array(), $order_by = "", $ascending = true) {
    return select_entries(
      "binet",
      array("subsidy_provider", "current_term", "id"),
      array("name", "clean_name"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function update_binet($binet, $hash) {
    if (isset($hash["name"])) {
      $hash["clean_name"] = clean_string($hash["name"]);
    }
    update_entry("binet",
                  array(),
                  array("description", "name", "clean_name", "subsidy_steps"),
                  $binet,
                  $hash);
  }

  /*
  @param int $binet sets binet to be subsidy_provider if not 0 , tinyint(1) NOT NULL DEFAULT '0' in table 'binet'
  @param string $subsidy_steps text information about how to use/get subsidy, text in table 'binet'
  */
  function set_subsidy_provider($binet) {
    update_entry(
      "binet",
      array("subsidy_provider"),
      array(),
      $binet,
      array("subsidy_provider" => 1)
    );
  }

  function unset_subsidy_provider($binet) {
    update_entry(
      "binet",
      array("subsidy_provider"),
      array(),
      $binet,
      array("subsidy_provider" => 0)
    );
  }

  /*
  @param int $binet id of the binet , int(11) NOT NULL in table 'binet'

  @return array Array containing all admins of current binet
  */
  function select_admins($binet, $term) {
    $sql = "SELECT student AS id
            FROM binet_admin
            WHERE binet = :binet AND term = :term AND rights = ".editing_rights;
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_viewers($binet, $term) {
    $sql = "SELECT student AS id
            FROM binet_admin
            WHERE binet = :binet AND term = :term AND rights = ".viewing_rights;
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  function select_current_admins($binet) {
    $sql = "SELECT DISTINCT binet_admin.student AS id
    FROM binet_admin
    INNER JOIN binet
    WHERE binet_admin.binet = :binet AND binet_admin.term = binet.current_term AND binet_admin.binet = binet.id AND binet_admin.rights = ".editing_rights;
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'

    @uses $_SESSION["student"] to fill `student` int(11) DEFAULT NULL in table 'binet_admin' for insert
  */
  function add_admin_binet($student, $binet, $term) {
    $sql = "INSERT INTO binet_admin(student, binet, term, rights)
            VALUES(:student, :binet, :term, ".editing_rights.")";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':student', $student, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->execute();
  }

  function add_viewer_binet($student, $binet, $term) {
    $sql = "INSERT INTO binet_admin(student, binet, term, rights)
            VALUES(:student, :binet, :term, ".viewing_rights.")";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':student', $student, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->execute();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'

    @uses $_SESSION["student"] to fill `student` int(11) DEFAULT NULL in table 'binet' for select
  */

  function status_member_term($binet_term) {
    $term = select_term_binet($binet_term, array("binet", "term"));
    $sql = "SELECT rights
            FROM binet_admin
            WHERE binet = :binet AND term = :term AND student = :student";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $term["binet"], PDO::PARAM_INT);
    $req->bindValue(':term', $term["term"], PDO::PARAM_INT);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    $results = $req->fetchAll();
    $viewing_rights = false;
    foreach ($results as $result) {
      switch ($result["rights"]) {
        case editing_rights:
        return editing_rights;
        case viewing_rights:
        $viewing_rights = true;
      }
    }
    if ($viewing_rights) {
      return viewing_rights;
    } else {
      return no_rights;
    }
  }

  function status_admin_binet($binet, $term = NULL) {
    $sql = "SELECT *
            FROM binet_admin
            WHERE binet = :binet ".(is_empty($term) ? "" : "AND term = :term ")."AND student = :student AND rights = ".editing_rights."
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    if (!is_empty($term)) {
      $req->bindValue(':term', $term, PDO::PARAM_INT);
    }
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    $results = $req->fetchAll();
    return !is_empty($results);
  }

  function status_viewer_binet($binet, $term) {
    $connected_student = connected_student();
    if ($connected_student) {
      $terms = select_terms(array("binet" => $binet, "term" => $term, "student" => $connected_student, "rights" => viewing_rights));
      return !is_empty($terms);
    } else {
      return false;
    }
  }

  function status_admin_current_binet($binet) {
    $sql = "SELECT *
    FROM binet_admin
    INNER JOIN binet
    ON binet_admin.binet = binet.id AND binet_admin.term = binet.current_term
    WHERE binet_admin.binet = :binet AND binet_admin.student = :student AND binet_admin.rights = ".editing_rights."
    LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    $results = $req->fetchAll();
    return !is_empty($results);
  }

  function current_term($binet) {
    return select_binet($binet, array("current_term"))["current_term"];
  }
  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet_admin'
    @param int $student if of the student, int(11) NOT NULL in table 'binet_admin'
  */
  function remove_admin_binet($student, $binet, $term) {
    $sql = "DELETE
            FROM binet_admin
            WHERE binet = :binet AND term = :term AND student = :student AND binet_admin.rights = ".editing_rights;
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->bindValue(':student', $student, PDO::PARAM_INT);
    $req->execute();
  }

  function remove_viewer_binet($student, $binet, $term) {
    $sql = "DELETE
            FROM binet_admin
            WHERE binet = :binet AND term = :term AND student = :student AND binet_admin.rights = ".viewing_rights;
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->bindValue(':student', $student, PDO::PARAM_INT);
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
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->execute();
  }

  /*
    @param int $binet id of the binet ,int(11) NOT NULL in table 'binet'
  */
  function change_term_binet($binet, $term) {
    update_entry(
      "binet",
      array("current_term"),
      array(),
      $binet,
      array("current_term" => $term)
    );
  }
