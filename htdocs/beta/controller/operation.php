<?php

  function creator_operation_or_kessier() {
    $operation = select_operation($_GET["operation"], array("created_by", "state"));
    header_if(!(($operation["created_by"] == $_SESSION["student"] && $operation["state"] == "suggested") || (is_current_kessier() && $operation["state"] == "waiting_validation")), 401);
  }

  function check_not_validated() {
    $operation = select_operation($GLOBALS["operation"]["id"], array("state"));
    header_if($operation["state"] != "waiting_validation", 403);
  }

  before_action("check_csrf_get", array("validate", "reject"));
  before_action("check_entry", array("edit", "update", "validate", "reject"), array("model_name" => "operation"));
  before_action("current_kessier", array("validate", "reject"));
  before_action("creator_operation_or_kessier", array("edit", "update"));
  before_action("create_form", array("new", "create", "edit", "update"), "operation_entry");
  before_action("check_form", array("create", "update"), "operation_entry");
  before_action("check_not_validated", array("validate", "reject"));

  $form_fields = array("comment", "bill", "payment_ref", "amount", "type", "paid_by", "sign", "binet", "term");

  switch ($_GET["action"]) {

  case "index":
    $operations = select_operations(array("created_by" => $_SESSION["student"], "binet_validation_by" => NULL), "date");
    break;

  case "new":
    break;

  case "create":
    $term = current_term($_POST["binet"]) + $_POST["next_term"];
    $operation["id"] = create_operation($_POST["binet"], $term, $_POST["amount"], $_POST["type"], $_POST);
    $_SESSION["notice"][] = "L'opération a été créée avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    foreach (select_admins($_POST["binet"], $term) as $student) {
      send_email($student["id"], "Nouvelle opération", "new_operation", array("operation" => $operation["id"], "student" => connected_student(), "binet" => $_POST["binet"], "term" => $term));
    }
    redirect_to_path(path("show", "operation", $operation["id"], binet_prefix($_POST["binet"], $term)));
    break;

  case "edit":
    break;

  case "update":
    $_POST["term"] = current_term($_POST["binet"]) + $_POST["next_term"];
    update_operation($operation["id"], $_POST);
    $_SESSION["notice"][] = "L'opération a été mise à jour avec succès. Il faut à présent qu'elle soit validée par un administrateur du binet.";
    redirect_to_path(path("show", "operation", $operation["id"], binet_prefix($_POST["binet"], $_POST["term"])));
    break;

  case "validate":
    kes_validate_operation($operation["id"]);
    $operation = select_operation($operation["id"], array("id", "binet", "term"));
    $_SESSION["notice"][] = "L'opération a été validée avec succès.";
    foreach (select_admins($operation["binet"], $operation["term"]) as $student) {
      send_email($student["id"], "Opération validée", "operation_validated", array("operation" => $operation["id"], "binet" => $operation["binet"], "term" => $operation["term"]));
    }
    redirect_to_path(path("validation", "binet", binet_term_id(KES_ID, select_binet(KES_ID, array("current_term"))["current_term"])));
    break;

  case "reject":
    kes_reject_operation($operation["id"]);
    $operation = select_operation($operation["id"], array("id", "binet", "term"));
    $_SESSION["notice"][] = "Tu as refusé l'opération. Elle apparaitra à nouveau dans les validations des administrateurs du binet. Tu peux leur envoyer un mail pour expliquer la raison du refus.";
    foreach (select_admins($operation["binet"], $operation["term"]) as $student) {
      send_email($student["id"], "Opération refusée par la Kès", "operation_rejected", array("operation" => $operation["id"], "kessier" => connected_student()));
    }
    redirect_to_path(path("validation", "binet", binet_term_id(KES_ID, select_binet(KES_ID, array("current_term"))["current_term"])));
    break;

  default:
    header_if(true, 403);
    exit;
  }
