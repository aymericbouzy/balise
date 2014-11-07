<?php

  function not_sent() {
    header_if(select_request($_GET["request"], array("sent"))["sent"], 403);
  }

  before_action("check_entry", array("show", "edit", "update", "delete", "send"), array("model_name" => "request", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "create", "edit", "update", "delete", "send"));
  before_action("not_sent", array("send", "edit", "update", "delete"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "Ta demande de subvention a été sauvegardée dans tes brouillons.";
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "Ta demande de subvention a été mise à jour avec succès.";
    break;

  case "delete":
    $_SESSION["notice"] = "Ta demande de subvention a été supprimée de tes brouillons.";
    break;

  case "send":
    $_SESSION["notice"] = "Ta demande de subvention a été envoyée avec succès.";
    break;

  default:
    header_if(true, 403);
    exit;
  }
