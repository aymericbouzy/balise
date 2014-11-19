<?php

  function exists_operation_type($operation_type) {
    return select_operation_type($operation_type) ? true : false;
  }

  function select_operation_type($operation_type, $fields = array()) {
    return select_entry(
      "operation_type",
      array("id", "name"),
      $operation_type,
      $fields
    );
  }

  function select_operation_types() {
    return select_entries(
      "operation_types",
      array("id"),
      array(),
      array(),
      array(),
      "id"
    );
  }
