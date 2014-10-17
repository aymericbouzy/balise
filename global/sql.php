<?php

  function select_entries($table, $selectable_int_fields, $selectable_str_fields, $criteria, $order_by = NULL, $ascending = true) {
    $sql = "SELECT id
            FROM ".$table."
            WHERE true";
    foreach ($criteria as $column => $value) {
      if ($column === "tags") {
        $i = 0;
        foreach($value as $tag) {
          if ($table == "budget") {
            $sql .= " AND EXISTS (SELECT *
                                  FROM budget_tag
                                  WHERE budget_tag.budget = budget.id AND budget_tag.tag = :tag".$i.")";
          } else {
            $sql .= " AND EXISTS (SELECT *
                                  FROM budget_tag
                                  INNER JOIN ".$table."_budget
                                  ON ".$table."_budget.budget = budget_tag.budget
                                  WHERE ".$table."_budget.".$table." = ".$table.".id AND budget_tag.tag = :tag".$i.")";
          }
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
    if ($order_by) {
      $sql .= " ORDER BY :order_by".($ascending ? " ASC" : " DESC");

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
    if ($order_by) {
      $req->bindParam(':order_by', $order_by, PDO::PARAM_STR);
    }
    $req->execute();
    return $req->fetchAll();
  }

  function update_entry($table, $updatable_int_fields, $updatable_str_fields, $entry, $hash) {
    foreach ($hash as $column => $value) {
      if (in_array($column, $updatable_int_fields) || in_array($column, $updatable_str_fields)) {
        $sql = "UPDATE ".$table."
                SET :column = :value
                WHERE id = :".$table."
                LIMIT 1";
        $req = Database::get()->prepare($sql);
        $req->bindParam(':'.$table, $entry, PDO::PARAM_INT);
        if (in_array($column, $updatable_int_fields)) {
          $req->bindParam(':'.$value, $value, PDO::PARAM_INT);
        } elseif (in_array($column, $updatable_str_fields)) {
          $req->bindParam(':'.$value, $value, PDO::PARAM_STR);
        }
        $req->bindParam(':'.$column, $column, PDO::PARAM_STR);
        $req->execute();
      }
    }
  }

  function select_entry($table, $id, $fields = NULL) {
    $sql = "SELECT ";
    if ($fields) {
      $initial = true;
      foreach ($fields as $field) {
        if ($initial) {
          $initial = false;
        } else {
          $sql .= ", ";
        }
        $sql .= $field;
      }
    } else {
      $sql .= "*";
    }
    $sql .= " FROM ".$table."
            WHERE id = :id
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
  }
