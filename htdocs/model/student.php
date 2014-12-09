<?php

  function exists_student($student) {
    return select_student($student) ? true : false;
  }

  function select_student($student, $fields = array()) {
    $binet = select_entry(
      "student",
      array("id", "name", "mail", "hruid"),
      $student,
      $fields
    );
    return $binet;
  }

  function select_students($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries(
      "student",
      array(),
      array("name", "mail", "hruid"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function create_student($hruid, $name, $mail) {
    $values["name"] = $name;
    $values["hruid"] = $hruid;
    $values["mail"] = $mail;
    return create_entry(
      "student",
      array(),
      array("hruid", "name", "mail"),
      $values
    );
  }
