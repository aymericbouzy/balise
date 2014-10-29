<?php

  include "base.php";

  function check_tag() {
    header_if(!validate_input(array("tag")), 400);
    header_if(empty(select_tag($_GET["tag"]), array("id")), 404);
  }

  before_action("check_tag", array("show"));

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
