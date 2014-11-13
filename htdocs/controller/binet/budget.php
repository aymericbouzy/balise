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
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "budget",
    "str_fields" => array(array(label, 100), array("tags_string", $MAX_TAG_STRING_LENGTH)),
    "amount_fields" => array(array("amount", $MAX_AMOUNT)),
    "tags_string" => true,
    "redirect_to" => path($_GET["action"], "budget", "", binet_prefix($binet["id"], $term));
  ));
  before_action("budget_is_alone", array("edit", "update", "delete"));
  before_action("budget_does_not_change_sign", array("update"));
  before_action("budget_amount_not_null", array("create"));
  before_action("budget_sign_is_one_or_minus_one", array("create", "update"));

  switch ($_GET["action"]) {

  case "index":
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
