<?php

  include "base.php";

  function check_budget() {
    header_if(!validate_input(array("budget")), 400);
    header_if(empty(select_budget($_GET["budget"]), array("id")), 404);
  }

  function budget_is_alone() {
    header_if(!empty(select_operations_budget($_GET["budget"])) || !empty(select_subsidies_budget($_GET["subsidy"])), 403);
  }

  function budget_does_not_change_sign() {
    // TODO
  }

  before_action("check_budget", array("show", "edit", "update", "delete"));
  before_action("member_binet_term", array("new", "create", "edit", "update", "delete"));
  before_action("budget_is_alone", array("delete"));
  before_action("budget_does_not_change_sign", array("update"));

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

  default:
    header_if(true, 403);
    exit;
  }
