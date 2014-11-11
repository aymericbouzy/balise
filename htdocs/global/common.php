<?php

  function redirect_to($hash) {
    if (isset($hash["action"])) {
      $hash["path"] = path($hash["action"], $_GET["controller"], ($GLOBALS[$_GET["controller"]]["id"] ?: ""), ($_GET["prefix"] == "binet" ? "binet/".$binet["id"]."/".$term : ""));
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
