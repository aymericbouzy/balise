<?php

  include "base.php";

  function check_operation() {
    header_if(!validate_input(array("operation")), 400);
    header_if(empty(select_operation($_GET["operation"]), array("id")), 404);
  }

  before_action("check_operation", array("show", "edit", "update", "delete", "validate"));
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
