<?php

  function binet_term_id($binet, $term) {
    return (select_binet($binet, array("clean_name"))["clean_name"])."/".$term;
  }

  function binet_prefix($binet, $term) {
    return "binet/".binet_term_id($binet, $term);
  }

  function redirect_to_action($action) {
    $path = path($action, $_GET["controller"], (isset($GLOBALS[$_GET["controller"]]["id"]) ? $GLOBALS[$_GET["controller"]]["id"] : ""), (isset($_GET["prefix"]) && $_GET["prefix"] == "binet" ? binet_prefix($GLOBALS["binet"], $GLOBALS["term"]) : ""));
    redirect_to_path($path);
  }

  function redirect_to_path($path) {
    header("Location: http".(empty($_SERVER["HTTPS"]) ? "" : "s")."://".$_SERVER["HTTP_HOST"]."/".$path);
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

  function connected_student() {
    if (validate_input(array("student"), array(), "session") && exists_student($_SESSION["student"])) {
      return $_SESSION["student"];
    }
    return false;
  }

  function set_editable_entry_for_form($table, $object, $form_fields) {
    $id = $object["id"];
    if (isset($_SESSION[$table])) {
      $object = initialise_for_form_from_session($form_fields, $table);
    } else {
      $object = call_user_func("select_".$table, $id, $form_fields);
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
    // TODO
    return false;
  }
