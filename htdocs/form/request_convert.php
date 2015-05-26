<?php

  $origin_action = "edit_converted_amount";
  $destination_action = "set_converted_amount";
  $id = $GLOBALS["request"]["id"];
  $form["redirect_to_if_error"] = path($origin_action, "request", $id, binet_prefix(binet, term));
  $form["destination_path"] = path($destination_action, "request", $id, binet_prefix(binet, term));
  $form["html_form_path"] = VIEW_PATH."binet/request/convert_form.php";

  foreach (select_subsidies(array("request" => $id, "conditional" => 1)) as $subsidy) {
    $subsidy = select_subsidy($subsidy["id"], array("id", "budget"));
    $budget = select_budget($subsidy["budget"], array("label"));
    $form["fields"]["amount_".$subsidy["id"]] = create_amount_field("le montant débloqué pour la subvention du budget \"".$budget["label"]."\"", array("optional" => 1));
  }

  function structured_converted_request_maker($validated_input) {
    $structured_input = array();
    foreach ($validated_input as $name => $value) {
      $matched_groups = array();
      if (preg_match("/^amount_([0-9]*)$/", $name, $matched_groups)) {
        $structured_input[$matched_groups[1]]["converted_amount"] = $value;
      }
    }
    return $structured_input;
  }

  $form["structured_input_maker"] = "structured_converted_request_maker";

  function initialise_request_form() {
    $initial_input = array();
    $request = $GLOBALS["request"]["id"];
    foreach (select_subsidies(array("request" => $request, "conditional" => 1)) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "converted_amount"));
      if (!is_empty($subsidy["converted_amount"])) {
        $initial_input["amount_".$subsidy["id"]] = $subsidy["converted_amount"];
      }
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_request_form";
