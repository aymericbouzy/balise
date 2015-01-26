<?php

  function check_admin() {
    header_if(!validate_input(array("admin")), 400);
    header_if(empty(select_terms(array("student" => $_GET["admin"], "binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"]))), 404);
    $GLOBALS[$admin] = $_GET["admin"];
  }

  before_action("check_csrf_post", array("create"));
  before_action("check_csrf_get", array("delete"));
  before_action("check_admin", array("delete"));
  before_action("current_kessier", array("new", "create", "delete"));
  before_action("check_form_input", array("create"), array(
    "model_name" => "admin",
    "int_fields" => array(array("term", 10000)),
    "other_fields" => array(array("student", "exists_student")),
    "redirect_to" => path("new", "admin", "", binet_prefix($binet, $term))
  ));
  before_action("generate_csrf_token", array("new", "index"));

  $form_fields = array("term", "student");

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    $admin = initialise_for_form_from_session($form_fields, "admin");
    break;

  case "create":
    add_admin_binet($_POST["student"], $binet, $_POST["term"]);
    send_email($_POST["student"], "Nouveau binet", "new_admin", array("binet_term" => $binet."/".$_POST["term"]));	
    $_SESSION["notice"][] = pretty_student($_POST["student"])." est à présent administrateur du binet ".pretty_binet($binet)." pour le mandat ".$_POST["term"].".";
    redirect_to_action("");
    break;

  case "delete":
    remove_admin_binet($admin, $binet, $term);
    $_SESSION["notice"][] = "Les droits d'administration de ".pretty_student($admin)." pour le mandat ".$term." du binet ".pretty_binet($binet)." ont été révoqués.";
    redirect_to_action("");
    break;

  default:
    header_if(true, 403);
    exit;
  }
