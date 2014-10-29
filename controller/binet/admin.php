<?php

  include "base.php";

  function check_admin() {
    header_if(!validate_input(array("admin")), 400);
    header_if(empty(select_student($_GET["admin"]), array("id")), 404);
  }

  before_action("check_admin", array("delete"));
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
