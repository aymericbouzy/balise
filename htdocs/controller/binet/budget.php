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

  before_action("check_entry", array("show", "edit", "update", "delete"), array("model_name" => "budget", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "new_expense", "new_income", "create", "edit", "update", "delete"));
  before_action("budget_is_alone", array("edit", "update", "delete"));
  before_action("budget_does_not_change_sign", array("update"));
  before_action("budget_amount_not_null", array("create"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "new_expense":
    break;

  case "new_income":
    break;

  case "create":
    $_SESSION["notice"] = "La ligne de budget a été créée avec succès.";
    redirect_to(array("action" => "show"));
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "La ligne de budget a été mise à jour avec succès.";
    redirect_to(array("action" => "show"));
    break;

  case "delete":
    redirect_to(array("action" => "index"));
    break;

  default:
    header_if(true, 403);
    exit;
  }
