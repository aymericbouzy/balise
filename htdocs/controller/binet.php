<?php

  before_action("check_binet_term", array("edit", "update", "set_subsidy_provider", "show", "change_term", "deactivate"));
  before_action("kessier", array("new", "create", "change_term", "deactivate", "set_subsidy_provider", "admin"));
  before_action("member_binet_term", array("edit", "update", "validation"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "Le binet ".$binet["name"]." a été créé avec succès.";
    redirect_to(array("action" => "show"));
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "Le binet ".$binet["name"]." a été mis à jour avec succès.";
    redirect_to(array("action" => "show"));
    break;

  case "set_subsidy_provider":
    $_SESSION["notice"] = "Le binet ".$binet["name"]." est devenu un binet subventionneur.";
    redirect_to(array("action" => "show"));
    break;

  case "show":
    break;

  case "change_term":
    $_SESSION["notice"] = "Le mandat actuel du binet ".$binet["name"]." a été mis à jour.";
    redirect_to(array("action" => "show"));
    break;

  case "deactivate":
    $_SESSION["notice"] = "Le binet ".$binet["name"]." a été désactivé avec succès.";
    redirect_to(array("action" => "show"));
    break;

  case "validation":
    $pending_validations_operations = pending_validations_operations($binet["id"], $term);
    if ($binet["id"] == $KES_ID) {
      $pending_validations_operations_kes = pending_validations_operations_kes();
    }
    break;

  case "admin":
    break;

  default:
    header_if(true, 403);
    exit;
  }
