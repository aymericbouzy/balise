<?php

  function exists_operation_type($operation_type) {
    return select_operation_type($operation_type) ? true : false;
  }

  function select_operation_type($operation_type, $fields = array()) {
    return false;
  }
