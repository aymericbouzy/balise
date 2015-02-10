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

  function setup_for_editing() {
    $GLOBALS["budgets_involved"] = select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array("<", 0)));
    $GLOBALS["requested_amount_array"] = array_map("adds_amount_prefix", $GLOBALS["budgets_involved"]);
    $GLOBALS["purpose_array"] = array_map("adds_purpose_prefix", $GLOBALS["budgets_involved"]);
    $GLOBALS["edit_form_fields"] = array_merge(array("answer", "wave"), $GLOBALS["requested_amount_array"], $GLOBALS["purpose_array"]);
    $total_amount = 0;
    foreach ($GLOBALS["requested_amount_array"] as $amount_field) {
      $total_amount += isset($_POST[$amount_field]) ? $_POST[$amount_field] : 0;
    }
    $_POST["total_amount_requested"] = $total_amount;
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
    $sql = "SELECT *
    FROM binet_admin
    INNER JOIN wave
    ON wave.binet = binet_admin.binet AND binet_admin.term = wave.term
    INNER JOIN request
    ON request.wave = wave.id
    WHERE request.id = :request AND binet_admin.student = :student";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':request', $GLOBALS["request"]["id"], PDO::PARAM_INT);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    $result = $req->fetch();
    return !is_empty($result);
  }

  function check_wave_parameter() {
    header_if(!validate_input(array("wave")), 400);
    header_if(!exists_wave($_GET["wave"]), 404);
    header_if(select_wave($_GET["wave"], array("state"))["state"] != "submission", 403);
  }

  function check_exists_spending_budget() {
    $budgets = select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array("<", 0)));
    header_if(is_empty($budgets), 403);
  }

  function check_no_existing_request() {
    $requests = select_requests(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "wave" => $_GET["wave"]));
    if (!is_empty($requests)) {
      $GLOBALS["request"] = $requests[0];
      redirect_to_action("show");
    }
  }

  before_action("check_wave_parameter", array("new"));
  before_action("check_no_existing_request", array("new"));
  before_action("check_exists_spending_budget", array("new"));
  before_action("check_csrf_post", array("update", "create", "grant"));
  before_action("check_csrf_get", array("delete", "send"));
  before_action("check_entry", array("show", "edit", "update", "delete", "send", "review", "grant"), array("model_name" => "request", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "send"));
  before_action("check_granting_rights", array("review", "grant"));
  before_action("setup_for_editing", array("new", "create", "edit", "update"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "request",
    "str_fields" => array_merge(array(array("answer", MAX_TEXT_LENGTH)), array_map("adds_max_length_purpose", $purpose_array)),
    "amount_fields" => array_map("adds_max_amount", array_merge($requested_amount_array, array("total_amount_requested"))),
    "other_fields" => array(array("wave", "exists_wave")),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "request", $_GET["action"] == "update" && isset($request) ? $request["id"] : "", binet_prefix($binet, $term), array("wave" => isset($_POST["wave"]) ? $_POST["wave"] : 0)),
    "optional" => array_merge($requested_amount_array, $purpose_array)
  ));
  before_action("setup_for_review", array("review", "grant"));
  before_action("check_form_input", array("grant"), array(
    "model_name" => "request",
    "str_fields" => array_map("adds_max_length_purpose", $explanation_array),
    "amount_fields" => array_map("adds_max_amount", $granted_amount_array),
    "redirect_to" => path("review", "request", $_GET["action"] == "grant" ? $request["id"] : "", binet_prefix($binet, $term)),
    "optional" => $granted_amount_array
  ));
  before_action("not_sent", array("send", "edit", "update", "delete"));
  before_action("sent_and_not_published", array("review", "grant"));
  before_action("generate_csrf_token", array("new", "edit", "show", "review"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    $request = initialise_for_form_from_session($edit_form_fields, "request");
    $request["wave"] = $_GET["wave"];
    $request["wave"] = select_wave($request["wave"], array("question", "id"));
    foreach ($budgets_involved as $budget) {
      $request["budget"][$budget["id"]] = select_budget($budget["id"], array("id", "label", "amount", "real_amount", "subsidized_amount_requested", "subsidized_amount_granted", "subsidized_amount_used"));
      foreach (select_tags(array("budget" => $budget["id"])) as $tag) {
        $request["budget"][$budget["id"]]["tags"][] = select_tag($tag["id"], array("id", "name"));
      }
    }
    break;

  case "create":
    $subsidies = array();
    foreach ($_POST as $field => $value) {
      $field_elements = explode("_", $field);
      if ($field_elements[0]."_" == amount_prefix && $value > 0) {
        $subsidy = array();
        $subsidy["budget"] = $field_elements[1];
        $subsidy["requested_amount"] = $value;
        $subsidy["optional_values"] = array("purpose" => $_POST[purpose_prefix.$field_elements[1]]);
        $subsidies[] = $subsidy;
      }
    }
    $request["id"] = create_request($_POST["wave"], $subsidies, $_POST["answer"]);
    $_SESSION["notice"][] = "Ta demande de subvention a été sauvegardée dans tes brouillons.";
    redirect_to_action("show");
    break;

  case "show":
    $request = select_request($request["id"], array("id", "budget", "answer", "sent", "wave", "state"));
    $request["wave"] = select_wave($request["wave"], array("id", "binet", "term", "state"));
    $binet_info = select_binet($binet, array("id", "name", "description", "current_term", "subsidy_provider", "subsidy_steps"));
    $binet_info = array_merge(select_term_binet($binet_info["id"]."/".$binet_info["current_term"], array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state")), $binet_info);
    break;

  case "edit":
    function request_to_form_fields($request) {
      foreach ($GLOBALS["budgets_involved"] as $budget) {
        $subsidies = select_subsidies(array("budget" => $budget["id"], "request" => $request["id"]));
        if (is_empty($subsidies)) {
          $request[adds_amount_prefix($budget)] = 0;
          $request[adds_purpose_prefix($budget)] = "";
        } else {
          $subsidy = select_subsidy($subsidies[0]["id"], array("requested_amount", "purpose"));
          $request[adds_amount_prefix($budget)] = $subsidy["requested_amount"]/100;
          $request[adds_purpose_prefix($budget)] = $subsidy["purpose"];
        }
      }
      return $request;
    }
    $request = set_editable_entry_for_form("request", $request, $edit_form_fields);
    $request["wave"] = select_wave($request["wave"], array("question", "id"));
    break;

  case "update":
    update_request($request["id"], array("answer" => $_POST["answer"]));
    foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "budget"));
      $form_field = amount_prefix.$subsidy["budget"];
      if (is_empty($_POST[$form_field])) {
        delete_subsidy($subsidy["id"]);
      }
    }
    foreach ($_POST as $field => $value) {
      $field_elements = explode("_", $field);
      if ($field_elements[0]."_" == amount_prefix && $value > 0) {
        $subsidies = select_subsidies(array("budget" => $field_elements[1], "request" => $request["id"]));
        if (is_empty($subsidies)) {
          create_subsidy($field_elements[1], $request["id"], $value, array("purpose" => $_POST[purpose_prefix.$field_elements[1]]));
        } else {
          update_subsidy($subsidies[0]["id"], array("requested_amount" => $value, "purpose" => $_POST[purpose_prefix.$field_elements[1]]));
        }
      }
    }
    $_SESSION["notice"][] = "Ta demande de subvention a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "review":
    $request_info = select_request($request["id"], array("id", "budget", "answer", "sent", "wave", "state"));
    function request_to_form_fields($request) {
      foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
        $subsidy = select_subsidy($subsidy["id"], array("id", "granted_amount", "explanation"));
        $request[adds_amount_prefix($subsidy)] = $subsidy["granted_amount"];
        $request[adds_explanation_prefix($subsidy)] = $subsidy["explanation"];
      }
      return $request;
    }
    $request = set_editable_entry_for_form("request", $request, $review_form_fields);
    $request_info["wave"] = select_wave($request_info["wave"], array("id", "binet", "term", "state"));
    $binet_info = select_binet($binet, array("id", "name", "description", "current_term", "subsidy_provider", "subsidy_steps"));
    $binet_info = array_merge(select_term_binet($binet_info["id"]."/".$binet_info["current_term"], array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state")), $binet_info);
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
    $request = select_request($request["id"], array("id", "wave", "binet", "term"));
    $wave = select_wave($request["wave"], array("id", "binet", "term"));
    $_SESSION["notice"][] = "La demande de subvention du binet ".pretty_binet($request["binet"], $request["term"])." a été étudiée.";
    redirect_to_path(path("show", "wave", $wave["id"], binet_prefix($wave["binet"], $wave["term"])));
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
