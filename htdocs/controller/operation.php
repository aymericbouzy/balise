<?php

  function creator_operation_or_kessier() {
    $operation = select_operation($_GET["operation"], array("created_by", "binet_validation_by", "kes_validation_by"));
    header_if(($operation["created_by"] != $_SESSION["student"] || !empty($operation["binet_validation_by"])) && (!status_binet_admin($KES_ID) || !empty($operation["kes_validation_by"])), 401);
  }

  before_action("check_entry", array("show", "edit", "update", "validate", "reject"), array("model_name" => "operation");
  before_action("current_kessier", array("validate", "reject"));
  before_action("creator_operation_or_kessier", array("show", "edit", "update"));

  switch ($_GET["action"]) {

  case "index":
    $operations = select_operations(array("created_by" => $_SESSION["student"], "binet_validation_by" => NULL), "date");
    break;

  case "new":
    break;

  case "new_expense":
    break;

  case "new_income":
    break;

  case "create":
    $_SESSION["notice"][] = "L'opération a été créée avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"][] = "L'opération a été mise à jour avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    redirect_to_action("show");
    break;

  case "validate":
    kes_validate_operation($operation["id"]);
    $_SESSION["notice"][] = "L'opération a été validée avec succès.";
    redirect_to_path(path("validation", "binet", binet_term_id($KES_ID, select_binet($KES_ID, array("current_term"))["current_term"])));
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
