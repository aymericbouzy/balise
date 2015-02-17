<?php

  function check_admin() {
    header_if(!validate_input(array("admin")), 400);
    $terms = select_terms(array("student" => $_GET["admin"], "binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"]));
    header_if(is_empty($terms), 404);
    header_if($GLOBALS["binet"] == KES_ID && $_GET["admin"] == connected_student(), 401);
    $GLOBALS["admin"] = $_GET["admin"];
  }

  before_action("check_csrf_post", array("create"));
  before_action("check_csrf_get", array("delete"));
  before_action("check_admin", array("delete"));
  before_action("current_kessier", array("new", "create", "delete"));
  before_action("check_form_input", array("create"), array(
    "model_name" => "admin",
    "int_fields" => array(array("term", 1)),
    "other_fields" => array(array("student", "exists_student")),
    "redirect_to" => path("new", "admin", "", binet_prefix($binet, $term)),
    "optional" => array("term")
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
    $admin_term = current_term($binet) + $_POST["term"];
    foreach (select_admins($binet, $admin_term) as $student) {
      send_email($student["id"], "Nouvel administrateur du binet ".pretty_binet_term($binet."/".$admin_term, false, false), "new_admin_binet", array("admin" => $_POST["student"], "binet_term" => $binet."/".$admin_term));
    }
    add_admin_binet($_POST["student"], $binet, $admin_term);
    send_email($_POST["student"], "Nouveau binet", "new_admin_rights", array("binet_term" => $binet."/".$admin_term));
    $_SESSION["notice"][] = pretty_student($_POST["student"])." est à présent administrateur du binet ".pretty_binet($binet)." pour la promotion ".$admin_term.".";
    redirect_to_action("");
    break;

  case "delete":
    send_email($admin, "Déchéance des droits d'administration du binet ".pretty_binet_term($binet."/".$term, false, false), "delete_admin_rights", array("binet_term" => $binet."/".$term, "kessier" => connected_student()));
    remove_admin_binet($admin, $binet, $term);
    foreach (select_admins($binet, $term) as $student) {
      send_email($student["id"], "Suppression d'un administrateur du binet ".pretty_binet_term($binet."/".$admin_term, false, false), "delete_admin_binet", array("admin" => $admin, "binet_term" => $binet."/".$term, "kessier" => connected_student()));
    }
    $_SESSION["notice"][] = "Les droits d'administration de ".pretty_student($admin)." pour la promotion ".$term." du binet ".pretty_binet($binet)." ont été révoqués.";
    redirect_to_action("");
    break;

  default:
    header_if(true, 403);
    exit;
  }
