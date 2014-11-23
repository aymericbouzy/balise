<?php

  function exists_student($student) {
    return select_student($student) ? true : false;
  }

  function select_student($student, $fields = array()) {
    return false;
  }
