<?php

  function check_viewing_operation_rights() {
    header_if(!(has_viewing_rights($GLOBALS["binet"], $GLOBALS["term"]) || has_editing_rights_for_suggested_operation($GLOBALS["operation"]["id"])), 401);
  }

  function has_editing_rights_for_suggested_operation($operation) {
    $operation = select_operation($operation, array("created_by", "state"));
    return $operation["created_by"] == connected_student() && $operation["state"] == "suggested";
  }

  function define_binet_budgets() {
    $operation = select_operation($GLOBALS["operation"]["id"], array("amount"));
    $GLOBALS["binet_budgets"] = select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array($operation["amount"] > 0 ? ">" : "<", 0)));
  }

  function check_exists_budget() {
    $operation = select_operation($GLOBALS["operation"]["id"], array("amount"));
    $budgets = select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array($operation["amount"] > 0 ? ">" : "<", 0)));
    if (is_empty($budgets)) {
      $_SESSION["warning"][] = "Avant de pouvoir faire apparaître cette opération dans ta trésorerie, tu dois créer un budget auquel l'associer.";
      redirect_to_path(path("", "validation", "", binet_prefix($GLOBALS["binet"], $GLOBALS["term"])));
    }
  }

  before_action("check_csrf_get", array("delete"));
  before_action("check_entry", array("show", "edit", "update", "delete", "validate", "review"), array("model_name" => "operation", "binet" => $binet, "term" => $term));
  before_action("define_binet_budgets", array("validate", "review"));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "validate", "review"));
  before_action("check_viewing_operation_rights", array("show"));
  before_action("create_form", array("new", "create", "edit", "update"), "operation_entry");
  before_action("check_form", array("create", "update"), "operation_entry");
  before_action("create_form", array("review", "validate"), "operation_review");
  before_action("check_form", array("validate"), "operation_review");
  before_action("check_exists_budget", array("review", "validate"));

  switch ($_GET["action"]) {

  case "index":
    $operations = array();
    foreach (select_operations(array_merge($query_array, array("binet" => $binet, "term" => $term)), "date",false) as $operation) {
      $operations[] = select_operation($operation["id"], array("id", "comment", "amount", "date", "type","term","binet"));
    }
    break;

  case "new":
    break;

  case "create":
    $operation["id"] = create_operation($binet, $term, $_POST["amount"], $_POST["type"], $_POST);
    $_SESSION["notice"][] = "L'opération a été créée avec succès. Il te reste à indiquer à quel(s) budget(s) cette opération se rapporte.";
    $budgets = select_budgets(array("binet" => $binet, "term" => $term, "amount" => array($_POST["sign"] ? "<" : ">", 0)));
    redirect_to_action("review");
    break;

  case "show":
    $operation = select_operation(
      $operation["id"],
      array("id", "binet_validation_by", "kes_validation_by", "binet", "term", "amount", "bill", "payment_ref", "state", "type", "comment", "paid_by")
    );
    $budgets = isset($operation["binet_validation_by"]) ? select_budgets_operation($operation["id"]) : select_budgets(array("binet" => $binet, "term" => $term));
    break;

  case "edit":
    break;

  case "update":
    $operation = select_operation($operation["id"], array("id", "amount", "binet_validation_by"));
    update_operation($operation["id"], $_POST);
    $_SESSION["notice"][] = "L'opération a été mise à jour avec succès.";
    if ($operation["amount"] != $_POST["amount"] && !is_empty($operation["binet_validation_by"])) {
      $_SESSION["notice"][] = "Le montant de l'opération a changé : tu dois donc l'attribuer à nouveau à ton budget.";
      remove_budgets_operation($operation["id"]);
      redirect_to_action("review");
    } else {
      redirect_to_action("show");
    }
    break;

  case "delete":
    $operation = select_operation($operation["id"], array("created_by", "binet_validation_by", "binet", "term", "id"));
    if (is_empty($operation["binet_validation_by"]) && !in_array(array("id" => $operation["created_by"]), select_admins($operation["binet"], $operation["term"]))) {
      send_email($operation["created_by"], "Opération refusée", "operation_refused", array("operation" => $operation["id"], "binet" => $operation["binet"]));
    }
    delete_operation($operation["id"]);
    remove_budgets_operation($operation["id"]);
    $_SESSION["notice"][] = "L'opération a été supprimée avec succès.";
    redirect_to_action("index");
    break;

  case "review":
    $operation = select_operation($operation["id"], array("id", "amount", "created_by","paid_by", "comment", "date", "binet", "term","type","bill","payment_ref"));
    break;

  case "validate":
    remove_budgets_operation($operation["id"]);
    add_budgets_operation($operation["id"], $_POST);
    validate_operation($operation["id"]);
    $operation = select_operation($operation["id"], array("id", "created_by", "state"));
    if ($operation["created_by"] != connected_student()) {
      send_email($operation["created_by"], "Opération acceptée", "operation_accepted", array("operation" => $operation["id"], "binet" => $binet));
    }
    $_SESSION["notice"][] = "L'opération a été ajoutée dans ton budget.".($operation["state"] == "waiting_validation" ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : "");
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
