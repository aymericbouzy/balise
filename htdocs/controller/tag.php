<?php

  before_action("check_entry", array("show"), array("model_name" => "tag"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "create":
    break;

  case "show":
    break;

  default:
    header_if(true, 403);
    exit;
  }
