<?php

  define("amount_prefix", "amount_");
  define("purpose_prefix", "purpose_");

  function adds_amount_prefix($object) {
    return amount_prefix.$object["id"];
  }

  function adds_purpose_prefix($object) {
    return purpose_prefix.$object["id"];
  }

  function budgets_involved() {
    return select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"]));
  }

  function subsidies_involved() {
    return select_subsidies(array("request" => $GLOBALS["request"]));
  }

  function adds_max_amount($amount) {
    return array($amount, MAX_AMOUNT);
  }

  function adds_max_length_purpose($purpose) {
    return array($purpose, 128);
  }

  function not_sent() {
    header_if(select_request($_GET["request"], array("sent"))["sent"], 403);
  }

  before_action("check_csrf_post", array("update", "create"));
  before_action("check_csrf_get", array("delete", "send"));
  before_action("check_entry", array("show", "edit", "update", "delete", "send"), array("model_name" => "request", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "send"));
  $requested_amount_array = array_map("adds_amount_prefix", budgets_involved());
  $purpose_array = array_map("adds_purpose_prefix", budgets_involved());
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "request",
    "str_fields" => array_merge(array(array("answer", 100000)), array_map("adds_max_length_purpose", $purpose_array)),
    "amount_fields" => array_map("adds_max_amount", $requested_amount_array),
    "other_fields" => array(array("wave", "exists_wave")),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "request", $_GET["action"] == "update" ? $request["id"] : "", binet_prefix($binet, $term))
  ));
  before_action("not_sent", array("send", "edit", "update", "delete"));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  $edit_form_fields = array_merge(array("answer", "wave"), $requested_amount_array, $purpose_array);

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    foreach (budgets_involved() as $budget) {
      $request["budget"][$budget["id"]] = select_budget($budget["id"], array("id", "label", "amount", "real_amount", "subsidized_amount_requested", "subsidized_amount_granted", "subsidized_amount_used"));
      foreach (select_tags(array("budget" => $budget["id"])) as $tag) {
        $request["budget"][$budget["id"]]["tags"][] = select_tag($tag["id"], array("id", "name"));
      }
    }
    break;

  case "create":
    $_SESSION["notice"][] = "Ta demande de subvention a été sauvegardée dans tes brouillons.";
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "edit":
    function request_to_form_fields($request) {
      foreach (budgets_involved() as $budget) {
        $subsidies = select_subsidies(array("budget" => $budget["id"]));
        if (empty($subsidies)) {
          $request[add_amount_prefix($budget)] = 0;
          $request[add_purpose_prefix($budget)] = "";
        } else {
          $subsidy = select_subsidy($subsidies[0]["id"], array("requested_amount", "purpose"));
          $request[add_amount_prefix($budget)] = $subsidy["requested_amount"];
          $request[add_purpose_prefix($budget)] = $subsidy["purpose"];
        }
      }
      return $request;
    }
    $request = set_editable_entry_for_form("request", $request, $edit_form_fields);
    break;

  case "update":
    $_SESSION["notice"][] = "Ta demande de subvention a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "delete":
    $_SESSION["notice"][] = "Ta demande de subvention a été supprimée de tes brouillons.";
    redirect_to_action("index");
    break;

  case "send":
    $_SESSION["notice"][] = "Ta demande de subvention a été envoyée avec succès.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
