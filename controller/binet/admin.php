<?php

  before_action("check_entry", array("delete"), array("model_name" => "admin", "binet" => $_GET["binet"], "term" => $_GET["term"]));
  before_action("kessier", array("new", "create", "delete"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    break;

  case "delete":
    break;

  default:
    header_if(true, 403);
    exit;
  }
