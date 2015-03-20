<?php

  if (in_array($_GET["action"], array("new", "create"))) {
    $origin_action = "new";
    $destination_action = "create";
  } else {
    $origin_action = "new_viewer";
    $destination_action = "create_viewer";
  }

  $form["redirect_to_if_error"] = path($origin_action, "admin", "", binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["destination_path"] = path($destination_action, "admin", "", binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["html_form_path"] = VIEW_PATH."binet/admin/form.php";
  $form["fields"]["student"] = create_id_field("le nouvel ".($origin_action == "new" ? "administrateur" : "observateur"), "student");
  $form["fields"]["next_term"] = create_boolean_field("la promotion du binet");
