<?php

  if (in_array($_GET["action"], array("new", "create"))) {
    $origin_action = "new";
    $destination_action = "create";
    $id = "";
  } else {
    $origin_action = "edit";
    $destination_action = "update";
    $id = $GLOBALS["request"]["id"];
  }
  $query_array = isset($_POST["wave"]) ? array("wave" => $_POST["wave"]) : array();
  $form["redirect_to_if_error"] = path($origin_action, "request", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]), $query_array);
  $form["destination_path"] = path($destination_action, "request", $id, binet_prefix($GLOBALS["binet"], $GLOBALS["term"]));
  $form["html_form_path"] = VIEW_PATH."binet/request/edit_form.php";

  foreach (select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array("<", 0))) as $budget) {
    $budget = select_budget($budget["id"], array("id", "label"));
    $form["fields"]["amount_".$budget["id"]] = create_amount_field("le montant demandé pour le budget \"".$budget["label"]."\"", array("optional" => 1));
    $form["fields"]["purpose_".$budget["id"]] = create_text_field("l'explication pour le montant demandé pour le budget \"".$budget["label"]."\"", array("optional" => 1));
  }
  $form["fields"]["wave"] = create_id_field("la vague de subvention", "wave");
  $form["fields"]["answer"] = create_text_field("la réponse à la question du binet subventionneur");

  function check_total_amount_positive($input) {
    $sum = 0;
    foreach ($input as $name => $value) {
      if (substr($name, 0, 7) == "amount_") {
        $sum += $value;
      }
    }
    if ($sum <= 0) {
      return "Tu n'as pas demandé d'argent dans ta demande de subventions.";
    }
    return "";
  }
  $form["validations"] = array("check_total_amount_positive");

  function structured_request_maker($validated_input) {
    $structured_input = array();
    foreach ($validated_input as $name => $value) {
      $matched_groups = array();
      if (preg_match("/^amount_([0-9]*)$/", $name, $matched_groups)) {
        $budget = $matched_groups[1];
        $optional_values = isset($validated_input["purpose_".$budget]) ? array("purpose" => $validated_input["purpose_".$budget]) : array();
        $structured_input["subsidies"][] = array("budget" => $budget, "requested_amount" => $value, "optional_values" => $optional_values);
      } elseif (substr($name, 0, 8) != "purpose_") {
        $structured_input[$name] = $value;
      }
    }
    return $structured_input;
  }

  $form["structured_input_maker"] = "structured_request_maker";

  function initialise_request_form() {
    $initial_input = array();
    if (isset($GLOBALS["request"]["id"])) {
      $request = $GLOBALS["request"]["id"];
      $initial_input = select_request($request, array("wave", "answer"));
      foreach (select_subsidies(array("request" => $request)) as $subsidy) {
        $subsidy = select_subsidy($subsidy["id"], array("budget", "id", "requested_amount", "purpose"));
        $initial_input["amount_".$subsidy["budget"]] = $subsidy["requested_amount"];
        $initial_input["purpose_".$subsidy["budget"]] = $subsidy["purpose"];
      }
    }
    return $initial_input;
  }
  $form["initialise_form"] = "initialise_request_form";
