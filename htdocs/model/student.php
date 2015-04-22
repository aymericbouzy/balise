<?php

  function exists_student($student) {
    return select_student($student) ? true : false;
  }

  function select_student($student, $fields = array()) {
    $binet = select_entry(
      "student",
      array("id", "name", "email", "hruid"),
      $student,
      $fields
    );
    return $binet;
  }

  function select_students($criteria = array(), $order_by = NULL, $ascending = true) {
    return select_entries(
      "student",
      array(),
      array("id", "name", "email", "hruid"),
      array(),
      $criteria,
      $order_by,
      $ascending
    );
  }

  function create_student($hruid, $name, $email) {
    $values["name"] = $name;
    $values["hruid"] = $hruid;
    $values["email"] = $email;
    return create_entry(
      "student",
      array(),
      array("hruid", "name", "email"),
      $values
    );
  }
