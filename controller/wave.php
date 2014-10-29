<?php

  include "base.php";

  before_action("check_wave", array("show"), array("model_name" => "tag"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "show":
    break;

  default:
    header_if(true, 403);
    exit;
  }
