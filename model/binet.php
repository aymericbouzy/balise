<?php

  /*
  Creates a new binet with at least one name.

  @param string $name name of the binet, varchar(50) NOT NULL in table 'binet' and 'binet_admin'
  @param int $admin id of the student as admin of the binet, int(11) DEFAULT NULL in table 'binet_admin'

  @return int Id of the binet

  @uses $_SESSION["student"] to fill `validated_by` int(11) DEFAULT NULL in table 'binet' for insert

  */
  function create_binet($name, $term) {
    $sql = "INSERT INTO binet(name, clean_name, current_term)
            VALUES(:name, :clean_name, :term)";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':name', $name, PDO::PARAM_STR);
    $req->bindParam(':url_name', slean_string($name), PDO::PARAM_STR);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
    $req->execute();
    $binet = $req->fetch(PDO::FETCH_ASSOC);
    return $binet["id"];
  }

  function select_binet($binet, $fields = NULL) {
    return select_entry("binet", $binet), $fields;
  }

  function select_binets($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries("binet",
                          array("subsidy_provider", "current_term"),
                          array("name", "clean_name"),
                          $criteria,
                          $order_by,
                          $ascending);
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
  function get_admins($binet, $term) {
    $sql = "SELECT *
            FROM student
            INNER JOIN binet_admin
            ON student.id = binet_admin.student
            WHERE binet_admin.binet = :binet AND binet_admin.term = :term";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
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
  function status_admin_binet($binet, $term) {
    $sql = "SELECT *
            FROM binet_admin
            WHERE binet = :binet AND term = :term AND student = :student
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
    $req->bindParam(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    if ($row = $req->fetch()) {
      return true;
    } else {
      return false;
    }
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
  function change_term_binet($binet, $term)
    $sql = "UPDATE binet
            SET term = :term
            WHERE id = :binet
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParam(':term', $term, PDO::PARAM_INT);
    $req->execute();
  }

  function solde_binet($binet, $term) {
    $solde = 0;
    foreach (select_budgets(array("binet" => $binet, "term" => $term)) as $budget) {
      $real_amount = get_real_amount_budget($budget["id"]);
      if ($real_amount < 0) {
        $solde += min(0, $real_amount + get_subsidized_amount_budget($budget["id"]));
      } else {
        $solde += $real_amount;
      }
    }
  }
