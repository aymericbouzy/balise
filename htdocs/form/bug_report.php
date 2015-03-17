<?php

  $form["redirect_to_if_error"] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : path("welcome", "home");
  $form["destination_path"] = path("bug_report", "home");
  $form["html_form_path"] = VIEW_PATH."home/bug_report.php";
  $form["fields"]["report"] = create_text_field("le rapport de bug");
  $form["fields"]["information"] = create_text_field("les informations complémentaires");

  function get_information() {
    $initial_input["information"] = get_debug_context();
    return $initial_input;
  }

  $form["initialise_form"] = "get_information";
