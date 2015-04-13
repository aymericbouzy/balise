<?php

  if (in_array($_GET["action"], array("new", "create"))) {
    $origin_action = "new";
    $destination_action = "create";
  } else {
    $origin_action = "new_viewer";
    $destination_action = "create_viewer";
  }

  $form["redirect_to_if_error"] = path($origin_action, "member", "", binet_prefix(binet, term));
  $form["destination_path"] = path($destination_action, "member", "", binet_prefix(binet, term));
  $form["html_form_path"] = VIEW_PATH."binet/member/form.php";
  $form["fields"]["student"] = create_id_field("le nouvel ".($origin_action == "new" ? "administrateur" : "observateur"), "student");
  $form["fields"]["next_term"] = create_boolean_field("la promotion du binet");

  function check_new_member($input) {
    if (!is_empty($input["student"][0])) {
      $criteria = array("binet" => binet, "term" => term, "student" => $input["student"][0]);
      if ($_GET["action"] == "create") {
        $criteria["rights"] = editing_rights;
      }
      $terms = select_terms($criteria);
      if (!is_empty($terms)) {
        if ($_GET["action"] == "create") {
          return "Cette personne a déjà les droits d'administration sur le mandat de ce binet.";
        } else {
          return "Cette personne est déjà observatrice du mandat de ce binet.";
        }
      }
    }
    return "";
  }

  $form["validations"] = array("check_new_member");
