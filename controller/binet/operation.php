<?php

  before_action("check_entry", array("show", "edit", "update", "delete", "validate"), array("model_name" => "operation", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "create", "edit", "update", "delete", "validate"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "L'opération a été créée avec succès.".(true ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : "");
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "L'opération a été mise à jour avec succès.";
    break;

  case "delete":
    $_SESSION["notice"] = "L'opération a été supprimée avec succès.";
    break;

  case "validate":
    $_SESSION["notice"] = "L'opération a été acceptée.".(true ? " Elle doit à présent être validée par un kessier pour apparaître dans les comptes." : ""):
    break;

  default:
    header_if(true, 403);
    exit;
  }
