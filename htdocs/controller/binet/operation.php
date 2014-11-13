<?php

  before_action("check_entry", array("show", "edit", "update", "delete", "validate"), array("model_name" => "operation", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "new_expense", "new_income", "create", "edit", "update", "delete", "validate"));

  switch ($_GET["action"]) {

  case "index":
    $operations = array();
    foreach (select_operations(array_merge($query_array, array("binet" => $binet["id"])), "date") as $operation) {
      $operations[] = select_operation($operation["id"], array("id", "comment", "amount", "date", "type"))
    }
    break;

  case "new":
    break;

  case "new_expense":
    break;

  case "new_income":
    break;

  case "create":
    $_SESSION["notice"] = "L'opération a été créée avec succès.".(true ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : "");
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "L'opération a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "delete":
    $_SESSION["notice"] = "L'opération a été supprimée avec succès.";
    redirect_to_action("index");
    break;

  case "validate":
    $_SESSION["notice"] = "L'opération a été acceptée.".(true ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : "");
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
