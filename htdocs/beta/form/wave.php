<?php

  if (in_array($_GET["action"], array("new", "create"))) {
    $origin_action = "new";
    $destination_action = "create";
    $id = "";
  } else {
    $origin_action = "edit";
    $destination_action = "update";
    $id = $GLOBALS["wave"]["id"];
  }
  $form["redirect_to_if_error"] = path($origin_action, "wave", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["destination_path"] = path($destination_action, "wave", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["html_form_path"] = VIEW_PATH."binet/wave/form.php";
  $form["fields"]["submission_date"] = create_date_field("la date limite de dépôt des demandes de subventions", array("min" => current_date()));
  $form["fields"]["expiry_date"] = create_date_field("la date limite d'utilisation des subventions", array("min" => current_date()));
  $form["fields"]["question"] = create_text_field("la question à poser aux binets");

  function check_expiry_later_than_submission($input) {
    if ($input["expiry_date"] <= $input["submission_date"]) {
      $fields = $GLOBALS["wave_form"]["fields"];
      return ucfirst($fields["expiry_date"]["human_name"])." doit être postérieure à ".$fields["submission_date"]["human_name"].".";
    }
    return "";
  }

  $form["validations"] = array("check_expiry_later_than_submission");

  function initialise_wave_form() {
    $initial_input = array();
    if (isset($GLOBALS["wave"]["id"])) {
      $wave = $GLOBALS["wave"]["id"];
      $initial_input = select_wave($wave);
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_wave_form";
