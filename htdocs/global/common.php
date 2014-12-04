<?php

  function binet_term_id($binet, $term) {
    return (select_binet($binet, array("clean_name"))["clean_name"])."/".$term;
  }

  function binet_prefix($binet, $term) {
    return "binet/".binet_term_id($binet, $term);
  }

  function redirect_to_action($action) {
    $path = path($action, $_GET["controller"], ($GLOBALS[$_GET["controller"]]["id"] ?: ""), ($_GET["prefix"] == "binet" ? binet_prefix($GLOBALS["binet"]["id"], $GLOBALS["term"]) : ""));
    redirect_to_path($path);
  }

  function redirect_to_path($path) {
    header("Location: ".$SCHEME."://".$HOST."/".$path);
    exit;
  }

  function initialise_for_form($fields, $array) {
    $object = array();
    foreach ($fields as $field) {
      $object[$field] = isset($array[$field]) ? $array[$field] : "";
    }
    return $object;
  }

  function preg_does_match($regex, $string) {
    return preg_match($regex, $string) === 1;
  }
