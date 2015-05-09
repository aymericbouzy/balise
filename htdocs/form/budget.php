<?php

  define("new_tag_submit_value", "+");

  if (in_array($_GET["action"], array("new", "create"))) {
    $origin_action = "new";
    $destination_action = "create";
    $id = "";
  } else {
    $origin_action = "edit";
    $destination_action = "update";
    $id = $GLOBALS["budget"]["id"];
  }
  $form["redirect_to_if_error"] = path($origin_action, "budget", $id, binet_prefix(binet, term));
  $form["destination_path"] = path($destination_action, "budget", $id, binet_prefix(binet, term));
  $form["html_form_path"] = VIEW_PATH."binet/budget/form.php";
  $form["fields"]["label"] = create_name_field("le nom du budget", array("optional" => $origin_action == "edit" ? 1 : 0));
  $form["fields"]["tags"] = create_id_field("la liste des mots-clefs", "tag", array("optional" => 1, "multiple" => 1));
  $form["fields"]["amount"] = create_amount_field("le montant du budget", array("optional" => $origin_action == "edit" ? 1 : 0));
  $form["fields"]["subsidized_amount"] = create_amount_field("le montant des subventions espérées", array("optional" => 1));
  $form["fields"]["sign"] = create_boolean_field("le choix recette/dépense", array("disabled" => $origin_action == "edit" ? 1 : 0));

  function check_no_tag_creation($input) {
    if (isset($input["submit"]) && $input["submit"] == new_tag_submit_value) {
      $_SESSION["return_to"] = $GLOBALS["budget_form"]["redirect_to_if_error"];
      set_if_exists($_SESSION["stored_errors"], $_SESSION["error"]);
      unset($_SESSION["error"]);
      redirect_to_path(path("new", "tag"));
    }
  }

  $form["validations"] = array("check_no_tag_creation");
  $form["ignore_format_errors_for_validation"] = 1;

  function structured_budget_maker($validated_input) {
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
      $tags_array = array();
      foreach (select_tags_budget($budget) as $tag) {
        $tags_array[] = $tag["id"];
      }
      $initial_input["tags"] = $tags_array;
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_budget_form";
