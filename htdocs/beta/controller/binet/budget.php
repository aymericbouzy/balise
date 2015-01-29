<?php

  function budget_is_alone() {
    header_if(!empty(select_operations_budget($_GET["budget"])) || !empty(select_subsidies_budget($_GET["budget"])), 403);
  }

  before_action("check_csrf_post", array("update", "create"));
  before_action("check_csrf_get", array("delete"));
  before_action("check_entry", array("show", "edit", "update", "delete"), array("model_name" => "budget", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "budget",
    "str_fields" => array(array("label", 100), array("tags_string", MAX_TAG_STRING_LENGTH)),
    "amount_fields" => array(array("amount", MAX_AMOUNT)),
    "int_fields" => ($_GET["action"] == "create" ? array(array("sign", 1)) : array()),
    "tags_string" => true,
    "redirect_to" => path($_GET["action"], "budget", $_GET["action"] == "update" ? $budget["id"] : "", binet_prefix($binet, $term)),
    "optional" => array_merge($_GET["action"] == "update" ? array("label", "amount") : array(), array("sign", "tags_string"))
  ));
  before_action("budget_is_alone", array("edit", "update", "delete"));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  $form_fields = array("label", "tags_string", "amount");
  if ($_GET["action"] == "new") {
    $form_fields[] = "sign";
  }

  switch ($_GET["action"]) {

  case "index":
    $budgets = array();
    foreach (select_budgets(array_merge($query_array, array("binet" => $binet, "term" => $term)), "date") as $budget) {
      $budgets[] = select_budget($budget["id"], array("id", "label", "amount", "real_amount", "subsidized_amount_granted", "subsidized_amount_used"));
    }
    break;

  case "new":
    $budget = initialise_for_form_from_session($form_fields, "budget");
    break;

  case "create":
    $budget["id"] = create_budget($binet, $term, (2*$_POST["sign"] - 1)*$_POST["amount"], $_POST["label"]);
    foreach ($tags as $tag) {
      add_tag_budget($tag, $budget["id"]);
    }
    $_SESSION["notice"][] = "La ligne de budget a été créée avec succès.";
    redirect_to_action("show");
    break;

  case "show":
    $budget = select_budget($budget["id"], array("id", "label", "binet", "amount", "term", "real_amount", "subsidized_amount_granted", "subsidized_amount_used"));
    break;

  case "edit":
    function budget_to_form_fields($budget) {
      $budget["sign"] = $budget["amount"] < 0 ? true : false;
      $budget["amount"] *= $budget["sign"] ? -1 : 1;
      $first = true;
      foreach (select_tags_budget($id) as $tag) {
        if ($first) {
          $first = false;
        } else {
          $budget["tags_string"] .= ";";
        }
        $budget["tags_string"] .= select_tag($tag["id"], array("name"))["name"];
      }
      return $budget;
    }
    $budget = set_editable_entry_for_form("budget", $budget, $form_fields);
    break;

  case "update":
    update_budget($budget["id"], $_POST);
    remove_tags_budget($budget["id"]);
    foreach ($tags as $tag) {
      add_tag_budget($tag, $budget);
    }
    unset($_SESSION["budget"]);
    $_SESSION["notice"][] = "La ligne de budget a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "delete":
    redirect_to_action("index");
    break;

  default:
    header_if(true, 403);
    exit;
  }
