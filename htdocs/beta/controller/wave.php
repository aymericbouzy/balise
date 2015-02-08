<?php

  before_action("check_entry", array("show"), array("model_name" => "wave"));

  switch ($_GET["action"]) {

  case "index":
    $waves = select_waves();
    break;

  case "show":
    $wave = select_wave($wave["id"], array("id", "submission_date", "expiry_date", "published", "binet", "term", "state"));
    break;

  default:
    header_if(true, 403);
    exit;
  }
