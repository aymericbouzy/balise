<?php

  before_action("check_entry", array("show", "edit", "update", "validate", "reject"), array("model_name" => "operation");
  before_action("kessier", array("validate", "reject"));

  function creator_operation_or_kessier() {
    $operation = select_operation($_GET["operation"], array("created_by", "binet_validation_by", "kes_validation_by"));
    header_if(($operation["created_by"] != $_SESSION["student"] || !empty($operation["binet_validation_by"])) && (!status_binet_admin($KES_ID) || !empty($operation["kes_validation_by"])), 401);
  }

  before_action("creator_operation_or_kessier", array("show", "edit", "update"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "L'opération a été créée avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "L'opération a été mise à jour avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    break;

  case "validate":
    $_SESSION["notice"] = "L'opération a été validée avec succès.";
    break;

  case "reject":
    $_SESSION["notice"] = "Tu as refusé l'opération. Elle apparaitra à nouveau dans les validations des administrateurs du binet.";
    break;

  default:
    header_if(true, 403);
    exit;
  }
