<?php

  $origin_action = "transfer";
  $destination_action = "copy";
  $form["redirect_to_if_error"] = path($origin_action, "budget", "", binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["destination_path"] = path($destination_action, "budget", "", binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["html_form_path"] = VIEW_PATH."binet/budget/transfer_form.php";

  foreach (select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"] - 1)) as $budget) {
    $budget = select_budget($budget["id"], array("id", "label"));
    $form["fields"]["budget_".$budget["id"]] = create_boolean_field("le choix du budget \"".$budget["label"]."\"", array("optional" => 1));
  }

  function structured_review_maker($validated_input) {
    $structured_input["budgets"] = array();
    foreach ($validated_input as $name => $value) {
      $matched_groups = array();
      if (!is_empty($value) && preg_match("/^budget_([0-9]*)$/", $name, $matched_groups)) {
        $structured_input["budgets"][] = $matched_groups[1];
      }
    }
    return $structured_input;
  }

  $form["structured_input_maker"] = "structured_review_maker";

  function initialise_budget_transfer_form() {
    $initial_input = array();
    foreach ($GLOBALS["budget_transfer_form"]["fields"] as $name => $field) {
      $initial_input[$name] = 1;
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_budget_transfer_form";
