<?php

  function operation_does_not_change_sign() {
    header_if($_POST["sign"] * select_operation($operation["id"], array("amount"))["amount"] < 0, 403);
  }

  before_action("check_csrf_post", array("update", "create"));
  before_action("check_csrf_get", array("delete", "validate"));
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
    $operation = initialise_for_form($form_fields, $_SESSION["operation"]);
    break;

  case "create":
    $operation = create_operation($binet, $term, $_POST["sign"]*$_POST["amount"], $_POST["type"], $_POST);
    $_SESSION["notice"][] = "L'opération a été créée avec succès.".(true ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : "");
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "edit":
    $id = $operation;
    if (isset($_SESSION["operation"])) {
      $operation = initialise_for_form($form_fields, $_SESSION["operation"]);
    } else {
      $operation = select_operation($operation, $form_fields);
      $operation["sign"] = $operation["amount"] > 0 ? true : false;
      $operation["amount"] *= $operation["sign"] ? 1 : -1;
      $operation = initialise_for_form($form_fields, $operation);
    }
    $operation["id"] = $id;
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
    $_SESSION["notice"][] = "L'opération a été acceptée.".(true ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : "");
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
