<?php

  before_action("check_binet_term", array("edit", "update", "set_subsidy_provider", "show", "change_term", "deactivate"));
  before_action("kessier", array("new", "create", "change_term", "deactivate", "set_subsidy_provider"));
  before_action("member_binet_term", array("edit", "update"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    break;

  case "edit":
    break;

  case "update":
    break;

  case "set_subsidy_provider":
    break;

  case "show":
    break;

  case "change_term":
    break;

  case "deactivate":
    break;

  case "validation":
    break;

  default:
    header_if(true, 403);
    exit;
  }
