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
      if ($column === "tags") {
        $i = 0;
        foreach($value as $tag) {
          $sql .= " AND EXISTS (SELECT * FROM ".$table."_tag WHERE ".$table."_tag.".$table." = ".$table.".id AND ".$table."_tag.tag = :tag".$i.")";
          $tags[$i] = $tag;
          $i++;
        }
      }
      if (in_array($column, $selectable_int_fields + $selectable_str_fields)) {
        $sql .= " AND ".$column;
        if (is_array($value)) {
          $sql .= " ".$value[0];
        } else {
          $sql .= " =";
        }
        $sql .= " :".$column;
      }
    }
    $req = Database::get()->prepare($sql);
    foreach ($criteria as $column => $value) {
      if ($column === "tags") {
        for ($j = 0; $j < $i, $j++) {
          $req->bindParam(':tag'.$j, $tags[$j], PDO::PARAM_INT);
        }
      } else {
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
    }
    $req->execute();
    return $req->fetchAll();
  }
