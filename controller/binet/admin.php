<?php

  before_action("check_entry", array("delete"), array("model_name" => "admin", "binet" => $binet["id"], "term" => $term));
  before_action("kessier", array("new", "create", "delete"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = $student["full_name"]." est à présent administrateur du binet ".$binet["name"]." pour le mandat ".$term.".";
    break;

  case "delete":
    $_SESSION["notice"] = "Les droits d'administration de "$student["full_name"]." pour le mandat ".$term." du binet ".$binet["name"]." ont été révoqués.";
    break;

  default:
    header_if(true, 403);
    exit;
  }
