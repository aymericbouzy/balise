<?php

  function sum_array($array, $column) {
    $sum = 0;
    foreach($array as $entry) {
      $sum += $entry[$column];
    }
    return $sum;
  }

  function select_entries($table, $selectable_int_fields, $selectable_str_fields, $criteria) {
    $sql = "SELECT *
            FROM ".$table."
            WHERE true";
    foreach ($criteria as $column => $value) {
      if (in_array($column, $selectable_int_fields + $selectable_str_fields)) {
        $sql .= " AND ".$column;
        if (is_array($value)) {
          $sql .= " ".$value[0];
        } else {
          $sql .= " =";
        }
        $sql .= " :".$column;
      }
      if ($column === "tags") {
        
      }
    }
    $req = Database::get()->prepare($sql);
    foreach ($criteria as $column => $value) {
      if (is_array($value)) {
        $value = $value[1];
      }
      if (is_null($value)) {
        $req->bindParam(':'.$column, NULL, PDO::PARAM_NULL);
      } elseif (in_array($column, $selectable_int_fields)) {
        $req->bindParam(':'.$column, $value, PDO::PARAM_INT);
      } elseif (in_array($column, $selectable_str_fields)) {
        $req->bindParam(':'.$column, $value, PDO::PARAM_STR);
      }
    }
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
  }
