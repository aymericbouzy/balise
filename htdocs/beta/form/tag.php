<?php

  $origin_action = "new";
  $destination_action = "create";
  $form["redirect_to_if_error"] = path($origin_action, "tag");
  $form["destination_path"] = path($destination_action, "tag");
  $form["html_form_path"] = VIEW_PATH."tag/form.php";
  $form["fields"]["name"] = create_name_field("le tag");

  function check_all_tags_exist($input) {
    if (isset($input["tags"])) {
      foreach (explode(";", $input["tags"]) as $tag_name) {
        $tag_name = remove_exterior_spaces($tag_name);
        if (!is_empty($tag_name)) {
          $tags = select_tags(array("clean_name" => clean_string($tag_name)));
          if (is_empty($tags)) {
            $_SESSION["tag_to_create"] = $tag_name;
            $_SESSION["return_to"] = $GLOBALS["budget_form"]["redirect_to_if_error"];
            redirect_to_path(path("new", "tag"));
          }
        }
      }
    }
    return "";
  }

  $form["validations"] = array("check_all_tags_exist");

  function structured_budget_maker($validated_input) {
    if (isset($validated_input["tags"])) {
      $tags_string = $validated_input["tags"];
      $validated_input["tags"] = array();
      foreach (explode(";", $tags_string) as $tag_name) {
        $tag_name = remove_exterior_spaces($tag_name);
        if (!is_empty($tag_name)) {
          $tags = select_tags(array("clean_name" => clean_string($tag_name)));
          $validated_input["tags"][] = $tags[0]["id"];
        }
      }
      $validated_input["tags"] = array_unique($validated_input["tags"]);
    }
    if (isset($GLOBALS["budget"])) {
      $existing_budget = select_budget($GLOBALS["budget"]["id"], array("amount"));
      $validated_input["sign"] = $existing_budget["amount"] > 0 ? 1 : 0;
    }
    if (!$validated_input["sign"]) {
      $validated_input["amount"] *= -1;
    }
    unset($validated_input["sign"]);
    return $validated_input;
  }

  $form["structured_input_maker"] = "structured_budget_maker";

  function initialise_budget_form() {
    $initial_input = array();
    if (isset($GLOBALS["budget"]["id"])) {
      $budget = $GLOBALS["budget"]["id"];
      $initial_input = select_budget($budget);
      $initial_input["sign"] = $initial_input["amount"] > 0 ? 1 : 0;
      if (!$initial_input["sign"]) {
        $initial_input["amount"] *= -1;
      }
      $tags_string = "";
      foreach (select_tags_budget($budget) as $tag) {
        $tags_string .= select_tag($tag["id"], array("name"))["name"]."; ";
      }
      $initial_input["tags"] = $tags_string === "" ? "" : substr($tags_string, 0, -2);
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_budget_form";
