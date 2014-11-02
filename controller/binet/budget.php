<?php

  function budget_is_alone() {
    header_if(!empty(select_operations_budget($_GET["budget"])) || !empty(select_subsidies_budget($_GET["subsidy"])), 403);
  }

  function budget_does_not_change_sign() {
    // TODO
  }

  function budget_amount_not_null() {
    // TODO
  }

  before_action("check_entry", array("show", "edit", "update", "delete"), array("model_name" => "budget", "binet" => $_GET["binet"], "term" => $_GET["term"]));
  before_action("member_binet_term", array("new", "create", "edit", "update", "delete"));
  before_action("budget_is_alone", array("edit", "update", "delete"));
  before_action("budget_does_not_change_sign", array("update"));
  before_action("budget_amount_not_null", array("create"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "La ligne de budget a été créée avec succès.";
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "La ligne de budget a été mise à jour avec succès.";
    break;

  case "delete":
    break;

  default:
    header_if(true, 403);
    exit;
  }
