<?php

  $origin_action = "new";
  $destination_action = "create";
  $form["redirect_to_if_error"] = path($origin_action, "tag");
  $form["destination_path"] = path($destination_action, "tag");
  $form["html_form_path"] = VIEW_PATH."tag/form.php";
  $form["fields"]["name"] = create_name_field("le tag");

  function check_unique_clean_name($input) {
    $criteria["clean_name"] = clean_string($input["name"]);
    $tags = select_tags($criteria);
    if (!is_empty($tags)) {
      return "Ce tag existe déjà sous la forme ".pretty_tag($tags[0]["id"]).".";
    }
    return "";
  }

  $form["validations"] = array("check_unique_clean_name");

  function initialise_tag_form() {
    $initial_input = array();
    if (isset($GLOBALS["tag"]["id"])) {
      $tag = $GLOBALS["tag"]["id"];
      $initial_input = select_tag($tag);
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_tag_form";
