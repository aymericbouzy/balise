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
      redirect_to_path(path("", "operation", "", binet_prefix($GLOBALS["binet"], $GLOBALS["term"])));
    }
  }

  function is_editable_operation($operation) {
    return is_empty(select_operation($operation, array("kes_validation_by"))["kes_validation_by"]);
  }

  function check_is_editable_operation() {
    header_if(!is_editable_operation($GLOBALS["operation"]["id"]), 403);
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
  before_action("check_is_editable_operation", array("edit", "update"));

  switch ($_GET["action"]) {

  case "index":
    $operations = array();
    $op_pending_kes_validations = array();
    foreach (select_operations(array_merge($query_array, array("binet" => $binet, "term" => $term)), "date",false) as $operation) {
      $operations[] = select_operation($operation["id"], array("id", "comment", "amount", "date", "type","term","binet"));
    }
    foreach (select_operations(array_merge($query_array, array("binet" => $binet, "term" => $term, "state" => "waiting_validation")), "date",false) as $operation){
      $op_pending_kes_validations[] = select_operation($operation["id"], array("id", "comment", "amount", "date", "type","term","binet"));
    }
    $pending_validations_operations = pending_validations_operations($binet, $term);
    break;

  case "new":
    break;

  case "create":
    $operation["id"] = create_operation($binet, $term, $_POST["amount"], $_POST["type"], $_POST);
    $_SESSION["notice"][] = "L'opération a été créée avec succès. Il te reste à indiquer à quel(s) budget(s) cette opération se rapporte.";
    $budgets = select_budgets(array("binet" => $binet, "term" => $term, "amount" => array($_POST["amount"] > 0 ? ">" : "<", 0)));
    redirect_to_action("review");
    break;

  case "show":
    $operation = select_operation(
      $operation["id"],
      array("id", "binet_validation_by", "kes_validation_by", "binet", "term", "amount", "bill", "bill_date", "payment_ref", "payment_date", "state", "type", "comment", "paid_by")
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
    $_SESSION["notice"][] = "L'opération a été ajoutée dans ton budget.";
    if ($operation["state"] == "waiting_validation") {
      $_SESSION["notice"][] = "Cette opération va utiliser des subventions ".list_to_human_string(concerned_subsidy_providers($operation["id"]), "pretty_binet").". Pour savoir comment faire valider ton opération par la Kès et récupérer tes subventions, tu peux aller consulter la page du binet subventionneur concerné.";
    }
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
