<?php

  before_action("check_csrf_post", array("create"));
  before_action("check_csrf_get", array("delete"));
  before_action("check_entry", array("delete"), array("model_name" => "admin", "binet" => $binet, "term" => $term));
  before_action("current_kessier", array("new", "create", "delete"));
  before_action("check_form_input", array("create"), array(
    "model_name" => "admin",
    "other_fields" => array(array("student", "exists_student")),
    "redirect_to" => path("new", "request", "", binet_prefix($binet, $term))
  ));
  before_action("generate_csrf_token", array("new", "index"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"][] = $admin["full_name"]." est à présent administrateur du binet ".$binet["name"]." pour le mandat ".$term.".";
    redirect_to_action("");
    break;

  case "delete":
    $_SESSION["notice"][] = "Les droits d'administration de ".$admin["full_name"]." pour le mandat ".$term." du binet ".$binet["name"]." ont été révoqués.";
    redirect_to_action("");
    break;

  default:
    header_if(true, 403);
    exit;
  }
