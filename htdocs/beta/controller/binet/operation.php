<?php

  define("amount_prefix", "amount_");

  function adds_amount_prefix($object) {
    return amount_prefix.$object["id"];
  }

  function adds_max_amount($amount) {
    return array($amount, MAX_AMOUNT);
  }

  $amount_array = array();

  function setup_for_validation() {
    $total_amount = select_operation($GLOBALS["operation"]["id"], array("amount"))["amount"];
    $GLOBALS["binet_budgets"] = select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array($total_amount > 0 ? ">" : "<", 0)));
    $GLOBALS["amount_array"] = array_map("adds_amount_prefix", $GLOBALS["binet_budgets"]);
    $amounts_sum = 0;
    foreach ($GLOBALS["amount_array"] as $key) {
      if (isset($_POST[$key])) {
        $amounts_sum += $_POST[$key];
      }
    }
    $_POST["amounts_sum"] = $amounts_sum;
  }

  function equals_operation_amount($sum_amount) {
    if ($sum_amount == abs(select_operation($GLOBALS["operation"]["id"], array("amount"))["amount"])) {
      return true;
    } else {
      $_SESSION["error"][] = "La somme des montants indiqués n'est pas égale au montant de l'opération.";
      return false;
    }
  }

  before_action("check_csrf_post", array("update", "create", "validate"));
  before_action("check_csrf_get", array("delete"));
  before_action("check_entry", array("show", "edit", "update", "delete", "validate", "review"), array("model_name" => "operation", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "validate", "review"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "operation",
    "str_fields" => array(array("bill", 30), array("reference", 30), array("comment", 255)),
    "amount_fields" => array(array("amount", MAX_AMOUNT)),
    "int_fields" => ($_GET["action"] == "create" ? array(array("sign", 1)) : array()),
    "other_fields" => array(array("type", "exists_operation_type"), array("paid_by", "exists_student")),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "operation", $_GET["action"] == "update" ? $operation["id"] : "", binet_prefix($binet, $term)),
    "optional" => array_merge(array("sign", "paid_by", "bill", "reference", "comment"), $_GET["action"] == "update" ? array("type", "amount") : array())
  ));
  before_action("setup_for_validation", array("validate", "review"));
  before_action("check_form_input", array("validate"), array(
    "model_name" => "operation",
    "amount_fields" => array_map("adds_max_amount", array_merge($amount_array, array("amounts_sum"))),
    "other_fields" => array(array("amounts_sum", "equals_operation_amount")),
    "redirect_to" => path("review", "operation", $_GET["action"] == "validate" ? $operation["id"] : "", binet_prefix($binet, $term)),
    "optional" => $amount_array
  ));
  before_action("generate_csrf_token", array("new", "edit", "show", "review"));

  $form_fields = array("comment", "bill", "reference", "amount", "type", "paid_by");
  if ($_GET["action"] == "new") {
    $form_fields[] = "sign";
  }

  switch ($_GET["action"]) {

  case "index":
    $operations = array();
    foreach (select_operations(array_merge($query_array, array("binet" => $binet, "term" => $term)), "date") as $operation) {
      $operations[] = select_operation($operation["id"], array("id", "comment", "amount", "date", "type"));
    }
    break;

  case "new":
    $operation = initialise_for_form_from_session($form_fields, "operation");
    break;

  case "create":
    $operation["id"] = create_operation($binet, $term, (1 - $_POST["sign"]*2)*$_POST["amount"], $_POST["type"], $_POST);
    $_SESSION["notice"][] = "L'opération a été créée avec succès. Il vous reste à indiquer à quel(s) budget(s) cette opération se rapporte.";
    redirect_to_action("review");
    break;

  case "show":
    $operation = select_operation($operation["id"], array("id", "binet_validation_by", "kes_validation_by", "binet", "term", "amount", "bill", "reference"));
    $budgets = isset($operation["binet_validation_by"]) ? select_budgets_operation($operation["id"]) : select_budgets(array("binet" => $binet, "term" => $term));
    break;

  case "edit":
    function operation_to_form_fields($operation) {
      $operation["sign"] = $operation["amount"] > 0 ? 1 : 0;
      $operation["amount"] *= $operation["sign"] ? 1 : -1;
      return $operation;
    }
    $operation = set_editable_entry_for_form("operation", $operation, $form_fields);
    break;

  case "update":
    unset($_SESSION["operation"]);
    $_SESSION["notice"][] = "L'opération a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "delete":
    $_SESSION["notice"][] = "L'opération a été supprimée avec succès.";
    redirect_to_action("index");
    break;

  case "review":
    if (!isset($_SESSION["operation"])) {
      $_SESSION["operation"] = array();
    }
    $id = $operation["id"];
    function operation_to_form_fields($operation) {
      foreach ($GLOBALS["binet_budgets"] as $budget) {
        $operation[adds_amount_prefix($budget)] = 0;
      }
      foreach (select_budgets_operation($operation["id"]) as $budget) {
        $operation[adds_amount_prefix($budget)] = $budget["amount"];
      }
      return $operation;
    }
    $operation = set_editable_entry_for_form("operation", $operation, $amount_array);
    $operation = array_merge($operation, select_operation($id, array("id", "amount", "binet_validation_by")));
    break;

  case "validate":
    $budget_amounts_array = array();
    foreach ($_POST as $key => $amount) {
      if ($amount > 0) {
        $budget_amounts_array[substr($key, strlen(amount_prefix))] = $amount;
      }
    }
    add_budgets_operation($operation["id"], $budget_amounts_array);
    validate_operation($operation["id"]);
    $_SESSION["notice"][] = "L'opération a été acceptée.".(true ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : "");
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
