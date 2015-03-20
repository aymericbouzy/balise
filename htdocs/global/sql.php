<?php

  function select_entries($table, $selectable_int_fields, $selectable_str_fields, $filterable_virtual_fields, $criteria, $order_by = NULL, $ascending = true) {
    $entries = select_with_request_string("id", $table, $selectable_int_fields, $selectable_str_fields, $criteria, $order_by, $ascending);
    return filter_entries($entries, $table, $filterable_virtual_fields, $criteria, $order_by, $ascending);
  }

  function sort_by_virtual_field($e1, $e2) {
    return ($GLOBALS["sort_by_virtual_field_ascending"] ? 1 : (-1))*strcmp($e1[$GLOBALS["sort_by_virtual_field_order_by"]], $e2[$GLOBALS["sort_by_virtual_field_order_by"]]);
  }

  function filter_entries($entries, $table, $filterable_virtual_fields, $criteria, $order_by = NULL, $ascending = true) {
    $virtual_criteria = array_intersect_key($criteria, array_flip($filterable_virtual_fields));
    if (!is_empty($virtual_criteria) || in_array($order_by, $filterable_virtual_fields)) {
      $virtual_entries = array();
      $virtual_fields = array_flip(array_intersect_key(array_flip($filterable_virtual_fields), $criteria));
      foreach($entries as $entry) {
        $virtual_entry = call_user_func("select_".$table, $entry["id"], array_merge($virtual_fields, array("id", $order_by)));
        $keep_entry = true;
        foreach($virtual_criteria as $column => $value) {
          if (is_array($value)) {
            switch ($value[0]) {
            case "=":
              $keep_entry = $keep_entry && $virtual_entry[$column] == $value[1];
              break;
            case "<":
              $keep_entry = $keep_entry && $virtual_entry[$column] < $value[1];
              break;
            case ">":
              $keep_entry = $keep_entry && $virtual_entry[$column] > $value[1];
              break;
            case "<=":
              $keep_entry = $keep_entry && $virtual_entry[$column] <= $value[1];
              break;
            case ">=":
              $keep_entry = $keep_entry && $virtual_entry[$column] >= $value[1];
              break;
            case "!=":
              $keep_entry = $keep_entry && $virtual_entry[$column] != $value[1];
              break;
            case "IS":
              switch ($value[1]) {
              case "NULL":
                $keep_entry = $keep_entry && is_null($virtual_entry[$column]);
                break;
              case "NOT NULL":
                $keep_entry = $keep_entry && !is_null($virtual_entry[$column]);
                break;
              }
              break;
            case "IN":
              $keep_entry = $keep_entry && in_array($virtual_entry[$column], $value[1]);
              break;
            case "NOT IN":
              $keep_entry = $keep_entry && !in_array($virtual_entry[$column], $value[1]);
              break;
            }
          } else {
            $keep_entry = $keep_entry && $virtual_entry[$column] == $value;
          }
        }
        if ($keep_entry) {
          $virtual_entries[] = $virtual_entry;
        }
      }
      if (!is_empty($order_by) && in_array($order_by, $filterable_virtual_fields)) {
        $GLOBALS["sort_by_virtual_field_order_by"] = $order_by;
        $GLOBALS["sort_by_virtual_field_ascending"] = $ascending;
        usort($virtual_entries, "sort_by_virtual_field");
      }
      return $virtual_entries;
    } else {
      return $entries;
    }
  }

  function select_with_request_string($select_string, $table, $selectable_int_fields, $selectable_str_fields, $criteria, $order_by = NULL, $ascending = true) {
    $sql = "SELECT DISTINCT ".$select_string."
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
          } elseif ($table == "binet") {
            $sql .= " AND EXISTS (SELECT *
                                  FROM budget_tag
                                  INNER JOIN budget
                                  ON budget.id = budget_tag.budget
                                  WHERE budget.binet = binet.id budget_tag.tag = :tag".$i.")";
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
      if (in_array($column, array_merge($selectable_int_fields, $selectable_str_fields))) {
        if (is_array($value) && $value[0] == "NOT IN" && is_empty($value[1])) {
          unset($criteria[$column]);
        } else {
          $sql .= " AND ".$column;

          if (is_array($value)) {
            $sql .= " ".$value[0];
          } else {
            $sql .= " =";
          }

          if (is_array($value) && $value[0] === "IS" && in_array($value[1], array("NULL", "NOT NULL"))) {
            $sql .= " ".$value[1];
          } elseif (is_array($value) && ($value[0] === "IN" || $value[0] === "NOT IN")) {
            $sql .= "(";
            $first = true;
            foreach ($value[1] as $index => $element) {
              if ($first) {
                $first = false;
              } else {
                $sql .= ",";
              }
              $sql .= ":".$column.$index;
            }
            $sql .= ")";
          } else {
            $sql .= " :".$column;
          }
        }
      }
    }
    if (!is_empty($order_by) && in_array($order_by, array_merge($selectable_int_fields, $selectable_str_fields))) {
      $sql .= " ORDER BY ".$order_by.($ascending ? " ASC" : " DESC");
    }
    $req = Database::get()->prepare($sql);
    foreach ($criteria as $column => $value) {
      if ($column === "tags") {
        for ($j = 0; $j < $i; $j++) {
          $req->bindValue(':tag'.$j, $tags[$j], PDO::PARAM_INT);
        }
      } else {
        $real_value = is_array($value) ? $value[1] : $value;
        if (!(is_array($value) && $value[0] === "IS" && in_array($value[1], array("NULL", "NOT NULL")))) {
          if (in_array($column, array_merge($selectable_int_fields, $selectable_str_fields))) {
            if (in_array($column, $selectable_int_fields)) {
              $pdo_option = PDO::PARAM_INT;
            } elseif (in_array($column, $selectable_str_fields)) {
              $pdo_option = PDO::PARAM_INT;
            }
            if (is_array($value) && ($value[0] == "IN" || $value[0] == "NOT IN")) {
              foreach ($value[1] as $index => $element) {
                $req->bindValue(':'.$column.$index, $element, $pdo_option);
              }
            } else {
              $req->bindValue(':'.$column, $real_value, $pdo_option);
            }
          }
        }
      }
    }
    $req->execute();
    return $req->fetchAll();
  }

  function update_entry($table, $updatable_int_fields, $updatable_str_fields, $entry, $hash) {
    foreach ($hash as $column => $value) {
      if (in_array($column, $updatable_int_fields) || in_array($column, $updatable_str_fields)) {
        $sql = "UPDATE ".$table."
                SET ".$column." = :value
                WHERE id = :".$table."
                LIMIT 1";
        $req = Database::get()->prepare($sql);
        $req->bindValue(':'.$table, $entry, PDO::PARAM_INT);
        if (in_array($column, $updatable_int_fields)) {
          $req->bindValue(':value', $value, PDO::PARAM_INT);
        } elseif (in_array($column, $updatable_str_fields)) {
          $req->bindValue(':value', $value, PDO::PARAM_STR);
        }
        $req->execute();
      }
    }
  }

  function select_entry($table, $selectable_fields, $id, $fields = array()) {
    $fields = array_intersect($fields, $selectable_fields);
    if (is_empty($fields)) {
      $fields = $selectable_fields;
    }
    $sql = "SELECT ";
    $initial = true;
    foreach ($fields as $field) {
      if ($initial) {
        $initial = false;
      } else {
        $sql .= ", ";
      }
      $sql .= $field;
    }
    $sql .= " FROM ".$table."
            WHERE id = :id
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
  }

  function create_entry($table, $creatable_int_fields, $creatable_str_fields, $values) {
    $values = array_intersect_key($values, array_flip(array_merge($creatable_int_fields, $creatable_str_fields)));
    $sql1 = "INSERT INTO ".$table."(";
    $sql2 = "VALUES(";
    $initial = true;
    foreach($values as $column => $value) {
      if ($initial) {
        $initial = false;
      } else {
        $sql1 .= ", ";
        $sql2 .= ", ";
      }
      $sql1 .= $column;
      if (is_array($value)) {
        switch ($value[0]) {
        case "date":
          $sql2 .= $value[1];
          break;
        default:
          $sql2 .= "NULL";
        }
      } else {
        $sql2 .= ":".$column;
      }
    }
    $req = Database::get()->prepare($sql1.") ".$sql2.")");
    foreach ($values as $column => $value) {
      if (!is_array($value)) {
        if (is_null($value)) {
          $req->bindValue(':'.$column, NULL, PDO::PARAM_NULL);
        } elseif (in_array($column, $creatable_int_fields)) {
          $req->bindValue(':'.$column, $value, PDO::PARAM_INT);
        } elseif (in_array($column, $creatable_str_fields)) {
          $req->bindValue(':'.$column, $value, PDO::PARAM_STR);
        }
      }
    }
    $req->execute();
    return Database::get()->lastInsertId("id");
  }

  function delete_entry($table, $entry) {
    $sql = "DELETE
    FROM ".$table."
    WHERE id = :entry";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':entry', $entry, PDO::PARAM_INT);
    $req->execute();
  }
