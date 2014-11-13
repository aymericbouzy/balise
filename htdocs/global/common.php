<?php

  function binet_term_id($binet, $term) {
    return (select_binet($binet, array("clean_name"))["clean_name"])."/".$term;
  }

  function binet_prefix($binet, $term) {
    return "binet/".binet_term_id($binet, $term);
  }

  function redirect_to($hash) {
    if (isset($hash["action"])) {
      //TODO change $binet["id"] to $binet["clean_name"]
      $hash["path"] = path($hash["action"], $_GET["controller"], ($GLOBALS[$_GET["controller"]]["id"] ?: ""), ($_GET["prefix"] == "binet" ? binet_prefix($GLOBALS["binet"]["id"], $GLOBALS["term"]) : ""));
    }
    header("Location: ".$SCHEME."://".$HOST."/".$hash["path"]);
    exit;
  }

  function initialise_for_form($fields, $array) {
    $object = array();
    foreach ($fields as $field) {
      $object[$field] = isset($array[$field]) ? $array[$field] : "";
    }
    return $object;
  }
