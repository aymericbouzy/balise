<?php

  $origin_action = "review";
  $destination_action = "grant";
  $id = $GLOBALS["request"]["id"];
  $form["redirect_to_if_error"] = path($origin_action, "request", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["destination_path"] = path($destination_action, "request", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["html_form_path"] = VIEW_PATH."binet/request/review_form.php";

  foreach (select_subsidies(array("request" => $id)) as $subsidy) {
    $subsidy = select_subsidy($subsidy["id"], array("id", "budget"));
    $budget = select_budget($subsidy["budget"], array("label"));
    $form["fields"]["amount_".$subsidy["id"]] = create_amount_field("le montant accordé au budget \"".$budget["label"]."\"", array("optional" => 1));
    $form["fields"]["explanation_".$subsidy["id"]] = create_text_field("l'explication pour le montant accordé au budget \"".$budget["label"]."\"", array("optional" => 1));
  }

  function structured_review_maker($validated_input) {
    $structured_input = array();
    foreach ($validated_input as $name => $value) {
      $matched_groups = array();
      if (preg_match("/^amount_([0-9]*)$/", $name, $matched_groups)) {
        $structured_input[$matched_groups[1]]["granted_amount"] = $value;
        $structured_input[$matched_groups[1]]["converted_amount"] = $value;
      } elseif (preg_match("/^explanation_([0-9]*)$/", $name, $matched_groups)) {
        $structured_input[$matched_groups[1]]["explanation"] = $value;
      }
    }
    return $structured_input;
  }

  $form["structured_input_maker"] = "structured_review_maker";

  function initialise_request_form() {
    $initial_input = array();
    $request = $GLOBALS["request"]["id"];
    foreach (select_subsidies(array("request" => $request)) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "granted_amount", "explanation"));
      if (!is_empty($subsidy["granted_amount"])) {
        $initial_input["amount_".$subsidy["id"]] = $subsidy["granted_amount"];
      }
      if (!is_empty($subsidy["explanation"])) {
        $initial_input["explanation_".$subsidy["id"]] = $subsidy["explanation"];
      }
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_request_form";
