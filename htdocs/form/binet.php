<?php

  if (in_array($_GET["action"], array("new", "create"))) {
    $origin_action = "new";
    $destination_action = "create";
    $id = "";
  } else {
    $id = $GLOBALS["binet"]["id"];
    if (in_array($_GET["action"], array("edit", "update"))) {
      $origin_action = "edit";
      $destination_action = "update";
      $binet = select_binet($id, array("subsidy_provider"));
    } else {
      $origin_action = "change_term";
      $destination_action = "reactivate";
    }
  }
  $form["redirect_to_if_error"] = path($origin_action, "binet", $id);
  $form["destination_path"] = path($destination_action, "binet", $id);
  $form["html_form_path"] = VIEW_PATH."binet/form.php";
  if ($origin_action != "change_term" && is_current_kessier()) {
    $form["fields"]["name"] = create_name_field("le nom du binet");
  }
  if (in_array($origin_action, array("new", "change_term"))) {
    $form["fields"]["term"] = create_name_field("la promotion");
  }
  if (has_editing_rights($id, current_term($id))) {
    $form["fields"]["description"] = create_text_field("la description publique du binet");
    if (!is_empty($binet["subsidy_provider"])) {
      $form["fields"]["subsidy_steps"] = create_text_field("la description des démarches à effectuer pour récupérer les subventions");
    }
  }

  function check_term_is_numeric($input) {
    if (isset($input["term"]) && (!is_numeric($input["term"]) || floor($input["term"]) != $input["term"])) {
      $fields = $GLOBALS["binet_form"]["fields"];
      return ucfirst($fields["term"]["human_name"])." doit être une année valide.";
    }
    return "";
  }

  function check_unique_clean_name($input) {
    if (isset($input["name"])) {
      $criteria["clean_name"] = clean_string($input["name"]);
      if (isset($GLOBALS["binet"]["id"])) {
        $criteria["id"] = array("!=", $GLOBALS["binet"]["id"]);
      }
      $binets = select_binets($criteria);
      if (!is_empty($binets)) {
        return "Ce nom de binet est déjà utilisé par le binet ".pretty_binet($binets[0]["id"]).". Tu dois en choisir un autre.";
      }
    }
    return "";
  }

  $form["validations"] = array("check_term_is_numeric", "check_unique_clean_name");

  function initialise_binet_form() {
    $initial_input = array();
    if ($_GET["action"] == "edit") {
      $binet = $GLOBALS["binet"]["id"];
      $initial_input = select_binet($binet);
    } else {
      $initial_input["term"] = current_term(KES_ID);
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_binet_form";
