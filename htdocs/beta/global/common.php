<?php

  function set_if_not_set(&$variable, $value) {
    if(!isset($variable)) {
      $variable = $value;
    }
  }

  function binet_term_id($binet, $term) {
    return (select_binet($binet, array("clean_name"))["clean_name"])."/".$term;
  }

  function binet_prefix($binet, $term) {
    return "binet/".binet_term_id($binet, $term);
  }

  function redirect_to_action($action) {
    $path = path($action, $_GET["controller"], (isset($GLOBALS[$_GET["controller"]]["id"]) && $_GET["action"] != "delete" ? $GLOBALS[$_GET["controller"]]["id"] : ""), (isset($_GET["prefix"]) && $_GET["prefix"] == "binet" ? binet_prefix($GLOBALS["binet"], $GLOBALS["term"]) : ""));
    redirect_to_path($path);
  }

  function redirect_to_path($path) {
    header("Location: ".full_path($path));
    exit;
  }

  function initialise_for_form_from_session($fields, $form_name) {
    return initialise_for_form($fields, isset($_SESSION[$form_name]) ? $_SESSION[$form_name] : array());
  }

  function initialise_for_form($fields, $array = array()) {
    $object = array();
    foreach ($fields as $field) {
      $object[$field] = isset($array[$field]) ? $array[$field] : "";
    }
    return $object;
  }

  function preg_does_match($regex, $string) {
    return preg_match($regex, $string) === 1;
  }

  function file_does_exist($file) {
    $file = substr($file, strlen(ROOT_PATH));
    if (substr($file, 0, 1) == "/") {
      $file = substr($file, 1);
    }
    return file_exists($file);
  }

  function connected_student() {
    if (validate_input(array("student"), array(), "session") && exists_student($_SESSION["student"])) {
      return $_SESSION["student"];
    }
    return false;
  }

  function set_editable_entry_for_form($table, $object, $form_fields) {
    $id = $object["id"];
    if (isset($_SESSION[$table]) && $_SESSION[$table] != array("errors" => array())) {
      $object = initialise_for_form_from_session($form_fields, $table);
    } else {
      $object = call_user_func("select_".$table, $id, $form_fields);
      $object["id"] = $id;
      $object = call_user_func($table."_to_form_fields", $object);
      $object = initialise_for_form($form_fields, $object);
    }
    $object["id"] = $id;
    if (!isset($_SESSION[$table]["errors"])) {
      $_SESSION[$table]["errors"] = array();
    }
    return $object;
  }

  function remove_exterior_spaces($string) {
    return preg_replace("/^\s*(\S(.*\S)?)\s*$/", "$1", $string);
  }

  function tag_is_selected($tag, $query_array) {
    return !empty($query_array["tags"]) && in_array(tag_to_clean_name($tag), $query_array["tags"]);
  }

  function tag_to_clean_name($tag) {
    return select_tag($tag, array("clean_name"))["clean_name"];
  }

  function tag_array_to_string($tags) {
    return implode("+", array_map("tag_to_clean_name", $tags));
  }

  function current_date() {
    return date("Y-m-d");
  }

  function ids_as_keys($array) {
    $returned_array = array();
    foreach ($array as $object) {
      $returned_array[$object["id"]] = $object;
    }
    return $returned_array;
  }
