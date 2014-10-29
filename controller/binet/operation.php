<?php

  include "base.php";

  before_action("check_entry", array("show", "edit", "update", "delete", "validate"), array("model_name" => "operation", "binet" => $_GET["binet"], "term" => $_GET["term"]));
  before_action("member_binet_term", array("new", "create", "edit", "update", "delete", "validate"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    break;

  case "delete":
    break;

  case "validate":
    break;

  default:
    header_if(true, 403);
    exit;
  }
