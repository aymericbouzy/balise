<?php

  define("amount_prefix", "amount_");
  define("purpose_prefix", "purpose_");
  define("explanation_prefix", "explanation_");

  $purpose_array = array();
  $requested_amount_array = array();
  $explanation_array = array();
  $granted_amount_array = array();

  function adds_amount_prefix($object) {
    return amount_prefix.$object["id"];
  }

  function adds_purpose_prefix($object) {
    return purpose_prefix.$object["id"];
  }

  function adds_explanation_prefix($object) {
    return explanation_prefix.$object["id"];
  }

  function setup_for_review() {
    $GLOBALS["subsidies_involved"] = select_subsidies(array("request" => $GLOBALS["request"]["id"]));
    $GLOBALS["granted_amount_array"] = array_map("adds_amount_prefix", $GLOBALS["subsidies_involved"]);
    $GLOBALS["explanation_array"] = array_map("adds_explanation_prefix", $GLOBALS["subsidies_involved"]);
    $GLOBALS["review_form_fields"] = array_merge($GLOBALS["granted_amount_array"], $GLOBALS["explanation_array"]);
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

  function sent_and_not_published() {
    $request = select_request($GLOBALS["request"]["id"], array("sent", "wave"));
    $wave = select_wave($request["wave"], array("published"));
    header_if($request["sent"] != 1 || $wave["published"] != 0, 403);
  }

  function check_granting_rights() {
    $request = select_request($GLOBALS["request"]["id"], array("wave"));
    $wave = select_wave($request["wave"], array("binet", "term"));
    header_if(!has_editing_rights($wave["binet"], $wave["term"]), 401);
  }

  function check_wave_parameter() {
    header_if(!validate_input(array("wave")), 400);
    header_if(!exists_wave($_GET["wave"]), 404);
    header_if(select_wave($_GET["wave"], array("state"))["state"] != "submission", 403);
  }

  function check_exists_spending_budget() {
    $budgets = select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array("<", 0)));
    if (is_empty($budgets)) {
      $_SESSION["warning"][] = "Avant de faire une demande de subventions, tu dois créer ton budget.";
      redirect_to_path(path("", "budget", "", binet_prefix($GLOBALS["binet"], $GLOBALS["term"])));
    }
  }

  function check_no_existing_request() {
    $requests = select_requests(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "wave" => $_GET["wave"]));
    if (!is_empty($requests)) {
      $GLOBALS["request"] = $requests[0];
      redirect_to_action("show");
    }
  }

  function check_rough_draft_viewing_rights() {
    $request = select_request($GLOBALS["request"]["id"], array("state"));
    header_if(!has_viewing_rights($GLOBALS["binet"], $GLOBALS["term"]) && $request["state"] == "rough_draft", 401);
  }

  before_action("check_wave_parameter", array("new"));
  before_action("check_no_existing_request", array("new"));
  before_action("check_exists_spending_budget", array("new"));
  before_action("check_csrf_post", array("update", "create", "grant"));
  before_action("check_csrf_get", array("delete", "send", "reject"));
  before_action("check_entry", array("show", "edit", "update", "delete", "send", "review", "grant", "reject"), array("model_name" => "request", "binet" => $binet, "term" => $term));
  before_action("check_rough_draft_viewing_rights", array("show"));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "send"));
  before_action("check_granting_rights", array("review", "grant", "reject"));
  before_action("create_form", array("new", "create", "edit", "update"), "request_entry");
  before_action("check_form", array("create", "update"), "request_entry");
  before_action("setup_for_review", array("review", "grant"));
  before_action("check_form_input", array("grant"), array(
    "model_name" => "request",
    "str_fields" => array_map("adds_max_length_purpose", $explanation_array),
    "amount_fields" => array_map("adds_max_amount", $granted_amount_array),
    "redirect_to" => path("review", "request", $_GET["action"] == "grant" ? $request["id"] : "", binet_prefix($binet, $term)),
    "optional" => $granted_amount_array
  ));
  before_action("not_sent", array("send", "edit", "update", "delete"));
  before_action("sent_and_not_published", array("review", "grant", "reject"));
  before_action("generate_csrf_token", array("new", "edit", "show", "review"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    $request["wave"] = select_wave($_GET["wave"], array("question", "id"));
    break;

  case "create":
    $request["id"] = create_request($request_entry_input["wave"], $request_entry_input["subsidies"], $request_entry_input["answer"]);
    $_SESSION["notice"][] = "Ta demande de subvention a été sauvegardée dans tes brouillons.";
    redirect_to_action("show");
    break;

  case "show":
    $request = select_request($request["id"], array("id", "budget", "answer", "sent", "wave", "state"));
    $request["wave"] = select_wave($request["wave"], array("id", "binet", "term", "state"));
    $current_binet = select_binet($binet, array("id", "name", "description", "current_term", "subsidy_provider", "subsidy_steps"));
    $current_binet = array_merge(select_term_binet($current_binet["id"]."/".$current_binet["current_term"], array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state")), $current_binet);
    break;

  case "edit":
    $request = select_request($request["id"], array("wave", "id"));
    $request["wave"] = select_wave($request["wave"], array("question", "id"));
    break;

  case "update":
    update_request($request["id"], array("answer" => $request_entry_input["answer"]));
    foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
      delete_subsidy($subsidy["id"]);
    }
    foreach ($request_entry_input["subsidies"] as $subsidy) {
      create_subsidy($subsidy["budget"], $request["id"], $subsidy["requested_amount"], $subsidy["optional_values"]);
    }
    $_SESSION["notice"][] = "Ta demande de subvention a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "review":
    function request_to_form_fields($request) {
      foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
        $subsidy = select_subsidy($subsidy["id"], array("id", "granted_amount", "explanation"));
        $request[adds_amount_prefix($subsidy)] = $subsidy["granted_amount"] / 100;
        $request[adds_explanation_prefix($subsidy)] = $subsidy["explanation"];
      }
      return $request;
    }
    $request = set_editable_entry_for_form("request", $request, $review_form_fields);
    $request_info = select_request($request["id"], array("id", "budget", "answer", "sent", "wave", "state"));
    $request_info["wave"] = select_wave($request_info["wave"], array("id", "binet", "term", "state"));
    $current_binet = select_binet($binet, array("id", "name", "description", "current_term", "subsidy_provider", "subsidy_steps"));
    $current_binet = array_merge(select_term_binet($current_binet["id"]."/".$current_binet["current_term"], array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state")), $current_binet);
    $previous_binet = select_term_binet($current_binet["id"]."/".($current_binet["current_term"] - 1), array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state"));
    $existing_subsidies = get_subsidized_amount_between($current_binet["id"]."/".$current_binet["current_term"], $request_info["wave"]["binet"]);
    $previous_subsidies = get_subsidized_amount_between($current_binet["id"]."/".($current_binet["current_term"] -  1), $request_info["wave"]["binet"]);
    break;

  case "grant":
    // TODO : check fields of POST can only be in $rewiew_form_fields
    foreach ($_POST as $field => $value) {
      $field_elements = explode("_", $field);
      switch ($field_elements[0]."_") {
        case amount_prefix:
          update_subsidy($field_elements[1], array("granted_amount" => $value));
          break;
        case explanation_prefix:
          update_subsidy($field_elements[1], array("explanation" => $value));
          break;
      }
    }
    review_request($request["id"]);
    $request = select_request($request["id"], array("id", "wave"));
    $wave = select_wave($request["wave"], array("id", "binet", "term"));
    $_SESSION["notice"][] = "La demande de subvention du binet ".pretty_binet($binet, $term)." a été étudiée.";
    redirect_to_path(path("show", "wave", $request["wave"], binet_prefix($wave["binet"], $wave["term"])));
    break;

  case "reject":
    foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
      update_subsidy($subsidy["id"], array("granted_amount" => 0));
    }
    review_request($request["id"]);
    $request = select_request($request["id"], array("id", "wave"));
    $wave = select_wave($request["wave"], array("id", "binet", "term"));
    $_SESSION["notice"][] = "La demande de subvention du binet ".pretty_binet($binet, $term)." a été marquée refusée. Nous vous conseillons tout de même de rajouter un commentaire explicatif.";
    redirect_to_path(path("show", "wave", $request["wave"], binet_prefix($wave["binet"], $wave["term"])));
    break;

  case "delete":
    $_SESSION["notice"][] = "Ta demande de subvention a été supprimée de tes brouillons.";
    redirect_to_action("index");
    break;

  case "send":
    send_request($request["id"]);
    $_SESSION["notice"][] = "Ta demande de subvention a été envoyée avec succès.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
