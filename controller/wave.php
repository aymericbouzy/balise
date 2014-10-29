<?php

  include "base.php";

  function check_wave() {
    header_if(!validate_input(array("wave")), 400);
    header_if(empty(select_wave($_GET["wave"]), array("id")), 404);
  }

  before_action("check_wave", array("show"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "show":
    break;

  default:
    header_if(true, 403);
    exit;
  }
