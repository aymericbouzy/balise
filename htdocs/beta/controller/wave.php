<?php

  before_action("check_entry", array("show"), array("model_name" => "wave"));

  switch ($_GET["action"]) {

  case "index":
    $waves = select_waves();
    break;

  case "show":
    break;

  default:
    header_if(true, 403);
    exit;
  }
