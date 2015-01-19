<?php

  function create_post_binet_term_for_check_form() {
    if (!empty($_POST["binet"]) && !empty($_POST["term"])) {
      $binet = select_binet($_POST["binet"], array("clean_name"))["clean_name"];
      $term = $_POST["term"];
      $_POST["binet_term"] = $binet."/".$term;
    }
  }

  function creator_operation_or_kessier() {
    $operation = select_operation($_GET["operation"], array("created_by", "binet_validation_by", "kes_validation_by"));
    header_if(($operation["created_by"] != $_SESSION["student"] || !empty($operation["binet_validation_by"])) && (!status_admin_current_binet(KES_ID) || !empty($operation["kes_validation_by"])), 401);
  }

  before_action("check_csrf_post", array("update", "create"));
  before_action("check_csrf_get", array("validate", "reject"));
  before_action("check_entry", array("show", "edit", "update", "validate", "reject"), array("model_name" => "operation"));
  before_action("current_kessier", array("validate", "reject"));
  before_action("creator_operation_or_kessier", array("show", "edit", "update"));
  before_action("create_post_binet_term_for_check_form", array("create", "update"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "operation",
    "str_fields" => array(array("bill", 30), array("reference", 30), array("comment", 255)),
    "amount_fields" => array(array("amount", MAX_AMOUNT)),
    "int_fields" => array(array("term", MAX_TERM)),
    "other_fields" => array(array("type", "exists_operation_type"), array("paid_by", "exists_student"), array("binet", "exists_binet"), array("binet_term", "exists_term_binet")),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "operation", $_GET["action"] == "update" ? $operation["id"] : ""),
    "optionnal" => array_merge(array("paid_by", "bill", "reference", "comment"), $_GET["action"] == "update" ? array("type", "amount") : array())
  ));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  $form_fields = array("comment", "bill", "reference", "amount", "type", "paid_by", "sign", "binet", "term");

  switch ($_GET["action"]) {

  case "index":
    $operations = select_operations(array("created_by" => $_SESSION["student"], "binet_validation_by" => NULL), "date");
    break;

  case "new":
    $operation = initialise_for_form_from_session($form_fields, "operation");
    break;

  case "create":
    $_SESSION["notice"][] = "L'opération a été créée avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    redirect_to_action("show");
    break;

  case "show":
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
    $_SESSION["notice"][] = "L'opération a été mise à jour avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    redirect_to_action("show");
    break;

  case "validate":
    kes_validate_operation($operation["id"]);
    $_SESSION["notice"][] = "L'opération a été validée avec succès.";
    redirect_to_path(path("validation", "binet", binet_term_id(KES_ID, select_binet(KES_ID, array("current_term"))["current_term"])));
    break;

  case "reject":
    kes_reject_operation($operation["id"]);
    $_SESSION["notice"][] = "Tu as refusé l'opération. Elle apparaitra à nouveau dans les validations des administrateurs du binet. Tu peux leur envoyer un mail pour expliquer la raison du refus.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
