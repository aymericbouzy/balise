<?php

  function check_return_to_is_set() {
    header_if(!isset($_SESSION["return_to"]), 403);
  }

  before_action("check_entry", array("show"), array("model_name" => "tag"));
  before_action("check_return_to_is_set", array("new", "create"));
  before_action("create_form", array("new", "create"), "tag");
  before_action("check_form", array("create"), "tag");

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $tag["id"] = create_tag($_POST["name"]);
    $_SESSION["notice"][] = "Le mot-clef \"".$_POST["name"]."\" a été créé avec succès.";
    $_SESSION["budget_form"]["tags"][] = $tag["id"];
    set_if_exists($_SESSION["error"], $_SESSION["stored_errors"]);
    unset($_SESSION["stored_errors"]);
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
