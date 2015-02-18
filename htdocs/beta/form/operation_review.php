<?php

  $origin_action = "review";
  $destination_action = "validate";
  $id = $GLOBALS["operation"]["id"];
  $expense = select_operation($id, array("amount"))["amount"] < 0;
  $form["redirect_to_if_error"] = path($origin_action, "operation", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["destination_path"] = path($destination_action, "operation", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["html_form_path"] = VIEW_PATH."binet/operation/review_form.php";

  foreach ($GLOBALS["binet_budgets"] as $budget) {
    $budget = select_budget($budget["id"], array("label", "id"));
    $form["fields"]["amount_".$budget["id"]] = create_amount_field("le montant attribué au budget \"".$budget["label"]."\"", array("optional" => 1));
  }

  function check_total_amount($input) {
    $sum = 0;
    foreach ($input as $name => $value) {
      if (substr($name, 0, 7) == "amount_") {
        $sum += $value;
      }
    }
    if ($sum != select_operation($GLOBALS["operation"]["id"], array("amount"))["amount"]) {
      return "La somme des montants indiqués n'est pas égale au montant total de l'opération.";
    }
    return "";
  }

  $form["validations"] = array("check_total_amount");

  function structured_allocation_maker($validated_input) {
    $structured_input = array();
    foreach ($validated_input as $name => $value) {
      if ($value > 0 && preg_match("/^amount_([0-9]*)$/", $name, $matched_groups)) {
        $structured_input[$matched_groups[1]] = $value;
      }
    }
    return $structured_input;
  }

  $form["structured_input_maker"] = "structured_allocation_maker";

  function initialise_allocation_form() {
    $initial_input = array();
    $operation = $GLOBALS["operation"]["id"];
    foreach (select_budgets_operation($operation) as $budget) {
      $initial_input["amount_".$budget["id"]] = $budget["amount"];
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_allocation_form";
