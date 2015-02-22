<?php

  if (in_array($_GET["action"], array("new", "create"))) {
    $origin_action = "new";
    $destination_action = "create";
    $id = "";
  } else {
    $origin_action = "edit";
    $destination_action = "update";
    $id = $GLOBALS["operation"]["id"];
  }
  $prefix = isset($_GET["prefix"]) && $_GET["prefix"] == "binet" ? binet_prefix($GLOBALS["binet"], $GLOBALS["term"]) : "";
  $form["redirect_to_if_error"] = path($origin_action, "operation", $id, $prefix);
  $form["destination_path"] = path($destination_action, "operation", $id, $prefix);
  $form["html_form_path"] = VIEW_PATH."binet/operation/form.php";
  if (is_empty($prefix)) {
    $form["fields"]["binet"] = create_id_field("le binet", "binet");
    $form["fields"]["next_term"] = create_boolean_field("la promotion du binet");
  }
  $form["fields"]["amount"] = create_amount_field("le montant de l'opération", array("optional" => $origin_action == "edit" ? 1 : 0));
  $form["fields"]["sign"] = create_boolean_field("le choix recette/dépense", array("disabled" => $origin_action == "edit" ? 1 : 0));
  $form["fields"]["bill"] = create_name_field("la référence de facture", array("optional" => 1));
  $form["fields"]["bill_date"] = create_date_field("la date de la facture", array("optional" => 1));
  $form["fields"]["payment_ref"] = create_name_field("la référence de paiement", array("optional" => 1));
  $form["fields"]["payment_date"] = create_date_field("la date de paiement", array("optional" => 1));
  $form["fields"]["comment"] = create_text_field("la description de l'opération", array("optional" => 1));
  $form["fields"]["type"] = create_id_field("le type de transaction", "operation_type");
  $form["fields"]["paid_by"] = create_id_field("la personne qui a payé", "paid_by");

  function structured_operation_maker($validated_input) {
    if (isset($GLOBALS["operation"])) {
      $existing_operation = select_operation($GLOBALS["operation"]["id"], array("amount"));
      $validated_input["sign"] = $existing_operation["amount"] > 0 ? 1 : 0;
    }
    if (!$validated_input["sign"]) {
      $validated_input["amount"] *= -1;
    }
    unset($validated_input["sign"]);
    return $validated_input;
  }

  $form["structured_input_maker"] = "structured_operation_maker";

  function initialise_operation_form() {
    $initial_input = array();
    if (isset($GLOBALS["operation"]["id"])) {
      $operation = $GLOBALS["operation"]["id"];
      $initial_input = select_operation($operation);
      $initial_input["sign"] = $initial_input["amount"] > 0 ? 1 : 0;
      if (!$initial_input["sign"]) {
        $initial_input["amount"] *= -1;
      }
    } elseif (is_empty($_GET["prefix"])) {
      $initial_input["next_term"] = 0;
    }
    return $initial_input;
  }

  $form["initialise_form"] = "initialise_operation_form";
