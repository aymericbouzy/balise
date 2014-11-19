<?php

  function budget_is_alone() {
    header_if(!empty(select_operations_budget($_GET["budget"])) || !empty(select_subsidies_budget($_GET["subsidy"])), 403);
  }

  function budget_sign_is_one_or_minus_one() {
    header_if(!isset($_POST["sign"]) || !is_numeric($_POST["sign"]) || !in_array($_POST["sign"], array(1, -1)), 400);
  }

  function budget_does_not_change_sign() {
    header_if($_POST["sign"] * select_budget($budget["id"], array("amount"))["amount"] < 0, 403);
  }

  function budget_amount_not_null() {
    if (isset($_POST["amount"]) && $_POST["amount"] == 0) {
      $_SESSION["budget"]["errors"][] = "amount";
      redirect_to_action("edit");
    }
  }

  before_action("check_entry", array("show", "edit", "update", "delete"), array("model_name" => "budget", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "new_expense", "new_income", "create", "edit", "update", "delete"));
  before_action("check_form_input", array("create", "update"), array_merge(array(
    "model_name" => "budget",
    "str_fields" => array(array("label", 100), array("tags_string", $MAX_TAG_STRING_LENGTH)),
    "amount_fields" => array(array("amount", $MAX_AMOUNT)),
    "tags_string" => true,
    "redirect_to" => path($_GET["action"], "budget", $_GET["action"] == "update" ? $budget["id"] : "", binet_prefix($binet["id"], $term))
  ), $_GET["action"] == "update" ? array("optionnal" => array("label", "amount")) : array()));
  before_action("budget_is_alone", array("edit", "update", "delete"));
  before_action("budget_sign_is_one_or_minus_one", array("create", "update"));
  before_action("budget_does_not_change_sign", array("update"));
  before_action("budget_amount_not_null", array("create", "update"));


  switch ($_GET["action"]) {

  case "index":
    $budgets = array();
    foreach (select_budgets(array_merge($query_array, array("binet" => $binet["id"], "term" => $term)), "date") as $budget) {
      $budgets[] = select_budget($budget["id"], array("id", "label", "amount", "real_amount", "subsidized_amount_granted", "subsidized_amount_used"));
    }
    break;

  case "new":
    break;

  case "new_expense":
    $budget = initialise_for_form(array("lebel", "tags_string", "amount"), $_SESSION["budget"]);
    break;

  case "new_income":
    $budget = initialise_for_form(array("label", "tags_string", "amount"), $_SESSION["budget"]);
    break;

  case "create":
    $budget = create_budget($binet["id"], $term, $_POST["sign"]*$_POST["amount"], $_POST["label"]);
    foreach ($tags as $tag) {
      add_tag_budget($tag, $budget);
    }
    $_SESSION["notice"] = "La ligne de budget a été créée avec succès.";
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    update_budget($budget["id"], $_POST);
    remove_tags_budget($budget["id"]);
    foreach ($tags as $tag) {
      add_tag_budget($tag, $budget);
    }
    $_SESSION["notice"] = "La ligne de budget a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "delete":
    redirect_to_action("index");
    break;

  default:
    header_if(true, 403);
    exit;
  }
