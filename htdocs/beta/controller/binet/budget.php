<?php

  function budget_is_alone() {
    $operations = select_operations_budget($_GET["budget"]);
    $subsidies = select_subsidies(array("budget" => $_GET["budget"]));
    return is_empty($operations) && is_empty($subsidies);
  }

  function check_budget_is_alone() {
    header_if(!budget_is_alone(), 403);
  }

  before_action("check_csrf_get", array("delete"));
  before_action("check_entry", array("show", "edit", "update", "delete"), array("model_name" => "budget", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete"));
  before_action("create_form", array("new", "create", "edit", "update"), "budget");
  before_action("check_form", array("create", "update"), "budget");
  before_action("check_budget_is_alone", array("edit", "update", "delete"));

  switch ($_GET["action"]) {

  case "index":
    $budgets = array();
    foreach (select_budgets(array_merge($query_array, array("binet" => $binet, "term" => $term)), "date") as $budget) {
      $budgets[] = select_budget($budget["id"], array("id", "label", "amount", "subsidized_amount", "real_amount", "subsidized_amount_granted", "subsidized_amount_used"));
    }
    break;

  case "new":
    break;

  case "create":
    $budget["id"] = create_budget($binet, $term, $_POST["amount"], $_POST["label"], $_POST["amount"] < 0 ? $_POST["subsidized_amount"] : NULL);
    foreach ($_POST["tags"] as $tag) {
      add_tag_budget($tag, $budget["id"]);
    }
    $_SESSION["notice"][] = "La ligne de budget a été créée avec succès.";
    redirect_to_action("index");
    break;

  case "show":
    $budget = select_budget($budget["id"], array("id", "label", "binet", "amount", "term", "real_amount","subsidized_amount", "subsidized_amount_granted", "subsidized_amount_used"));
    break;

  case "edit":
    break;

  case "update":
    update_budget($budget["id"], $_POST);
    remove_tags_budget($budget["id"]);
    foreach ($_POST["tags"] as $tag) {
      add_tag_budget($tag, $budget["id"]);
    }
    $_SESSION["notice"][] = "La ligne de budget a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "delete":
    delete_budget($budget["id"]);
    $_SESSION["notice"][] = "La ligne de budget a été supprimée avec succès.";
    redirect_to_action("index");
    break;

  default:
    header_if(true, 403);
    exit;
  }
