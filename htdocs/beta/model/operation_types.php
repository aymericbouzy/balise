<?php

  function exists_operation_type($operation_type) {
    return !empty(select_operation_type($operation_type));
  }

  function select_operation_type($operation_type, $fields = array()) {
    return select_entry(
      "operation_type",
      array("id", "name", "icon"),
      $operation_type,
      $fields
    );
  }

  function select_operation_types() {
    return select_entries(
      "operation_type",
      array("id"),
      array(),
      array(),
      array(),
      "id"
    );
  }
