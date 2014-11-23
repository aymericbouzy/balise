<?php

  function check_tag_is_set() {
    header_if(!isset($_SESSION["tag_to_create"]), 400);
  }

  function check_return_to_is_set() {
    header_if(!isset($_SESSION["return_to"]), 400);
  }

  before_action("check_entry", array("show"), array("model_name" => "tag"));
  before_action("check_tag_is_set", array("new", "create"));
  before_action("check_return_to_is_set", array("new", "create"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    create_tag($_SESSION["tag_to_create"]);
    $_SESSION["notice"] = "Le tag \"".$_SESSION["tag_to_create"]."\" a été créé avec succès.";
    $return_to = $_SESSION["return_to"];
    unset($_SESSION["return_to"]);
    redirect_to_path($return_to);
    break;

  case "show":
    break;

  default:
    header_if(true, 403);
    exit;
  }
