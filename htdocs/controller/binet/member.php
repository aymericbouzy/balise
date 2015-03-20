<?php

  function check_admin() {
    header_if(!validate_input(array("member")), 400);
    $terms = select_terms(array("student" => $_GET["member"], "binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "rights" => editing_rights));
    header_if(is_empty($terms), 404);
    header_if($GLOBALS["binet"] == KES_ID && $_GET["member"] == connected_student(), 401);
    $GLOBALS["admin"]["id"] = $_GET["member"];
  }

  function check_viewer() {
    header_if(!validate_input(array("member")), 400);
    $terms = select_terms(array("student" => $_GET["member"], "binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "rights" => viewing_rights));
    header_if(is_empty($terms), 404);
    $GLOBALS["viewer"]["id"] = $_GET["member"];
  }

  before_action("check_csrf_get", array("delete", "delete_viewer"));
  before_action("check_admin", array("delete"));
  before_action("check_viewer", array("delete_viewer"));
  before_action("current_kessier", array("new", "create", "delete"));
  before_action("check_editing_rights", array("new_viewer", "create_viewer", "delete_viewer"));
  before_action("create_form", array("new", "create", "new_viewer", "create_viewer"), "member");
  before_action("check_form", array("create", "create_viewer"), "member");

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
  case "new_viewer":
    $viewers = array_keys(ids_as_keys(select_viewers($binet, $term)));
    $admins = array_keys(ids_as_keys(select_admins($binet, $term)));
    $students = select_students(array("id" => array("NOT IN", array_merge($viewers, $admins))));
    break;

  case "create":
    $admin_term = current_term($binet) + $_POST["next_term"];
    foreach (select_admins($binet, $admin_term) as $student) {
      send_email($student["id"], "Nouvel administrateur du binet ".pretty_binet_term($binet."/".$admin_term, false, false), "new_admin_binet", array("admin" => $_POST["student"], "binet_term" => $binet."/".$admin_term));
    }
    add_admin_binet($_POST["student"], $binet, $admin_term);
    send_email($_POST["student"], "Nouveau binet", "new_admin_rights", array("binet_term" => $binet."/".$admin_term));
    $_SESSION["notice"][] = pretty_student($_POST["student"])." est à présent administrateur du binet ".pretty_binet($binet)." pour la promotion ".$admin_term.".";
    redirect_to_action("");
    break;

  case "delete":
    send_email($admin["id"], "Déchéance des droits d'administration du binet ".pretty_binet_term($binet."/".$term, false, false), "delete_admin_rights", array("binet_term" => $binet."/".$term, "kessier" => connected_student()));
    remove_admin_binet($admin["id"], $binet, $term);
    foreach (select_admins($binet, $term) as $student) {
      send_email($student["id"], "Suppression d'un administrateur du binet ".pretty_binet_term($binet."/".$term, false, false), "delete_admin_binet", array("admin" => $admin["id"], "binet_term" => $binet."/".$term, "kessier" => connected_student()));
    }
    $_SESSION["notice"][] = "Les droits d'administration de ".pretty_student($admin["id"])." pour la promotion ".$term." du binet ".pretty_binet($binet)." ont été révoqués.";
    redirect_to_action("");
    break;

  case "create_viewer":
    $admin_term = current_term($binet) + $_POST["next_term"];
    add_viewer_binet($_POST["student"], $binet, $admin_term);
    $_SESSION["notice"][] = pretty_student($_POST["student"])." peut à présent voir le détail de la trésorerie du binet ".pretty_binet($binet)." pour la promotion ".$admin_term.".";
    redirect_to_action("");
    break;

  case "delete_viewer":
    remove_viewer_binet($viewer["id"], $binet, $term);
    $_SESSION["notice"][] = "Les droits de regard de ".pretty_student($viewer["id"])." pour la promotion ".$term." du binet ".pretty_binet($binet)." ont été révoqués.";
    redirect_to_action("");
    break;

  default:
    header_if(true, 403);
    exit;
  }
