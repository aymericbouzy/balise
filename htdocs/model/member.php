<?php

  function select_members($criteria, $order_by = "", $ascending = true) {
    return select_with_request_string(
      "student as id",
      "binet_member",
      array("binet", "term", "student", "rights"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function select_admins($binet, $term) {
    return select_members(array("binet" => $binet, "term" => $term, "rights" => editing_rights));
  }

  function select_viewers($binet, $term) {
    return select_members(array("binet" => $binet, "term" => $term, "rights" => viewing_rights));
  }

  function select_current_admins($binet) {
    return select_admins($binet, current_term($binet));
  }

  function add_admin_binet($student, $binet, $term) {
    create_entry(
      "binet_member",
      array("student", "binet", "term", "rights"),
      array(),
      array(
        "student" => $student,
        "binet" => $binet,
        "term" => $term,
        "rights" => editing_rights
      )
    );
  }

  function add_viewer_binet($student, $binet, $term) {
    create_entry(
      "binet_member",
      array("student", "binet", "term", "rights"),
      array(),
      array(
        "student" => $student,
        "binet" => $binet,
        "term" => $term,
        "rights" => viewing_rights
      )
    );
  }

  function status_member_term($binet_term) {
    $connected_student = connected_student();
    if (!$connected_student) {
      return no_rights;
    }
    $binet_term = select_term_binet($binet_term, array("binet", "term"));
    $results = select_with_request_string(
      "rights",
      "binet_member",
      array("binet", "term", "student", "rights"),
      array(),
      array("binet" => $binet_term["binet"], "term" => $binet_term["term"], "student" => connected_student()),
      "rights",
      true
    );
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

  function status_admin_binet($binet, $term) {
    return status_member_term(term_id($binet, $term)) == editing_rights;
  }

  function status_viewer_binet($binet, $term) {
    return status_member_term(term_id($binet, $term)) == viewing_rights;
  }

  function status_admin_current_binet($binet) {
    return status_admin_binet($binet, current_term($binet));
  }

  function remove_admin_binet($student, $binet, $term) {
    $sql = "DELETE
            FROM binet_member
            WHERE binet = :binet AND term = :term AND student = :student AND rights = ".editing_rights;
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->bindValue(':student', $student, PDO::PARAM_INT);
    $req->execute();
  }

  function remove_viewer_binet($student, $binet, $term) {
    $sql = "DELETE
            FROM binet_member
            WHERE binet = :binet AND term = :term AND student = :student AND rights = ".viewing_rights;
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':term', $term, PDO::PARAM_INT);
    $req->bindValue(':student', $student, PDO::PARAM_INT);
    $req->execute();
  }
