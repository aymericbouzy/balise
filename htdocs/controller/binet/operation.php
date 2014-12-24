<?php

  define("amount_prefix", "amount_");

  function operation_does_not_change_sign() {
    header_if($_POST["sign"] * select_operation($operation["id"], array("amount"))["amount"] < 0, 403);
  }

  function adds_amount_prefix($object) {
    return amount_prefix.$object["id"];
  }

  function adds_max_amount($amount) {
    return array($amount, MAX_AMOUNT);
  }

  function setup_for_validation() {
    $total_amount = select_operation($GLOBALS["operation"]["id"], array("amount"))["amount"];
    $GLOBALS["binet_budgets"] = select_budgets(array("binet" => $binet, "term" => $term, "amount" => array($total_amount > 0 ? ">" : "<", 0)));
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
  before_action("check_entry", array("show", "edit", "update", "delete", "validate"), array("model_name" => "operation", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "validate"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "operation",
    "str_fields" => array(array("bill", 30), array("reference", 30), array("comment", 255)),
    "amount_fields" => array(array("amount", MAX_AMOUNT)),
    "other_fields" => array(array("type", "exists_operation_type"), array("paid_by", "exists_student")),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "operation", $_GET["action"] == "update" ? $operation["id"] : "", binet_prefix($binet, $term)),
    "optionnal" => array_merge(array("paid_by", "bill", "reference", "comment"), $_GET["action"] == "update" ? array("type", "amount") : array())
  ));
  before_action("setup_for_validation", array("validate"));
  before_action("check_form_input", array("validate"), array(
    "model_name" => "operation",
    "amount_fields" => array_map("adds_max_amount", $amount_array),
    "other_fields" => array(array("amounts_sum", "equals_operation_amount")),
    "redirect_to" => path("show", "operation", $operation["id"], binet_prefix($binet, $term)),
    "optionnal" => $amount_array
  ));
  before_action("sign_is_one_or_minus_one", array("create", "update"));
  before_action("operation_does_not_change_sign", array("update"));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  $form_fields = array("comment", "bill", "reference", "amount", "type", "paid_by", "sign");

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
    $operation = create_operation($binet, $term, $_POST["sign"]*$_POST["amount"], $_POST["type"], $_POST);
    $_SESSION["notice"][] = "L'opération a été créée avec succès. Il vous reste à indiquer à quel(s) budget(s) cette opération se rapporte.";
    redirect_to_action("show");
    break;

  case "show":
    if (!empty(select_operation($operation["id"], array("binet_validation_by"))["binet_validation_by"])) {
      $operation = initialise_for_form_from_session($amount_array, "operation");
    }
    break;

  case "edit":
    function operation_to_form_fields($operation) {
      $operation["sign"] = $operation["amount"] > 0 ? true : false;
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
