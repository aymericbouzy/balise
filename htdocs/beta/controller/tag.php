<?php

  function check_tag_is_set() {
    header_if(!isset($_SESSION["tag_to_create"]), 403);
  }

  function check_return_to_is_set() {
    header_if(!isset($_SESSION["return_to"]), 403);
  }

  function check_unique_clean_name() {
    $tags = select_tags(array("clean_name" => clean_string($_SESSION["tag_to_create"])));
    header_if(!is_empty($tags), 403);
  }

  before_action("check_csrf_get", array("create"));
  before_action("check_entry", array("show"), array("model_name" => "tag"));
  before_action("check_tag_is_set", array("new", "create"));
  before_action("check_return_to_is_set", array("new", "create"));
  before_action("check_unique_clean_name", array("new", "create"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    create_tag($_SESSION["tag_to_create"]);
    $_SESSION["notice"][] = "Le tag \"".$_SESSION["tag_to_create"]."\" a été créé avec succès.";
    $return_to = $_SESSION["return_to"];
    unset($_SESSION["return_to"]);
    unset($_SESSION["tag_to_create"]);
    redirect_to_path(path("new", "budget", "", $return_to));
    break;

  case "show":
    break;

  default:
    header_if(true, 403);
    exit;
  }
