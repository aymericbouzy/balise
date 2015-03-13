<?php

  function is_sendable($request) {
    $request = select_request($request, array("sending_date", "wave"));
    $wave = select_wave($request["wave"], array("published"));
    return is_empty($request["sending_date"]) && !$wave["published"];
  }

  function check_is_sendable() {
    header_if(!is_sendable($GLOBALS["request"]["id"]), 403);
  }

  function is_editable($request) {
    $request = select_request($request, array("sending_date"));
    return is_empty($request["sending_date"]);
  }

  function check_is_editable() {
    header_if(!is_editable($GLOBALS["request"]["id"]), 403);
  }

  function sent_and_not_published() {
    $request = select_request($GLOBALS["request"]["id"], array("sending_date", "wave"));
    $wave = select_wave($request["wave"], array("published"));
    header_if(is_empty($request["sending_date"]) || $wave["published"] != 0, 403);
  }

  function check_granting_rights() {
    $request = select_request($GLOBALS["request"]["id"], array("wave"));
    $wave = select_wave($request["wave"], array("binet", "term"));
    header_if(!has_editing_rights($wave["binet"], $wave["term"]), 401);
  }

  function check_wave_parameter() {
    header_if(!validate_input(array("wave")), 400);
    header_if(!exists_wave($_GET["wave"]), 404);
    header_if(!in_array(select_wave($_GET["wave"], array("state"))["state"], array("submission", "deliberation")), 403);
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

  function converted_amount_is_editable($request) {
    $request = select_request($request, array("id", "state", "wave"));
    if ($request["state"] != "accepted") {
      return false;
    }
    $wave = select_wave($request["wave"], array("binet", "term"));
    if (!has_editing_rights($wave["binet"], $wave["term"]) && !is_current_kessier()) {
      return false;
    }
    foreach (select_subsidies(array("request" => $request)) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("conditional"));
      if ($subsidy["conditional"]) {
        return true;
      }
    }
    return false;
  }

  function check_converted_amount_is_editable() {
    header_if(!converted_amount_is_editable($GLOBALS["request"]["id"]), 401);
  }

  function check_return_to_operation() {
    if (isset($_GET["operation"])) {
      header_if(!validate_input(array("operation")), 400);
      header_if(!exists_operation($_GET["operation"]), 404);
      select_operation($_GET["operation"], array("id", "binet", "term"));
      $_SESSION["return_to"] = path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"]));
    }
  }

  before_action("check_wave_parameter", array("new"));
  before_action("check_no_existing_request", array("new"));
  before_action("check_exists_spending_budget", array("new"));
  before_action("check_csrf_post", array("update", "create", "grant"));
  before_action("check_csrf_get", array("delete", "send", "reject"));
  before_action("check_entry", array("show", "edit", "update", "delete", "send", "review", "grant", "reject", "edit_converted_amount", "set_converted_amount"), array("model_name" => "request", "binet" => $binet, "term" => $term));
  before_action("check_rough_draft_viewing_rights", array("show", "delete"));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "send"));
  before_action("check_granting_rights", array("review", "grant", "reject"));
  before_action("create_form", array("new", "create", "edit", "update"), "request_entry");
  before_action("check_form", array("create", "update"), "request_entry");
  before_action("create_form", array("review", "grant"), "request_review");
  before_action("check_form", array("grant"), "request_review");
  before_action("check_is_sendable", array("send"));
  before_action("check_is_editable", array("edit", "update", "delete"));
  before_action("check_converted_amount_is_editable", array("edit_converted_amount", "set_converted_amount"));
  before_action("create_form", array("edit_converted_amount", "set_converted_amount"), "request_convert");
  before_action("check_form", array("set_converted_amount"), "request_convert");
  before_action("sent_and_not_published", array("review", "grant", "reject"));
  before_action("check_return_to_operation", array("edit_converted_amount"));

  switch ($_GET["action"]) {

  case "index":
    $rough_drafts = select_requests(array("binet" => $binet, "term" => $term, "state" => array("IN", array("rough_draft", "late_rough_draft", "overdue_rough_draft"))));
    $sent_requests = select_requests(array("binet" => $binet, "term" => $term, "state" => array("IN", array("sent", "reviewed_accepted", "reviewed_rejected"))));
    $accepted_requests = select_requests(array("binet" => $binet, "term" => $term, "state" => "accepted"));
    $published_requests = select_requests(array("binet" => $binet, "term" => $term, "state" => array("IN", array("accepted", "rejected"))));
    $binet_term = select_term_binet($binet."/".$term, array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "amount_requested_in_rough_drafts", "amount_requested_in_sent"));
    break;

  case "new":
    $request["wave"] = select_wave($_GET["wave"], array("question", "id", "description"));
    break;

  case "create":
    $request["id"] = create_request($_POST["wave"], $_POST["subsidies"], $_POST["answer"]);
    $_SESSION["notice"][] = "Ta demande de subvention a été sauvegardée dans tes brouillons.";
    redirect_to_action("index");
    break;

  case "show":
    $request = select_request($request["id"], array("id", "budget", "answer", "sending_date", "wave", "state"));
    $request["wave"] = select_wave($request["wave"], array("id", "binet", "term", "state"));
    $current_binet = select_binet($binet, array("id", "name", "description", "current_term", "subsidy_provider", "subsidy_steps"));
    $current_binet = array_merge(select_term_binet($current_binet["id"]."/".$current_binet["current_term"], array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state")), $current_binet);
    break;

  case "edit":
    $request = select_request($request["id"], array("wave", "id"));
    $request["wave"] = select_wave($request["wave"], array("question", "id", "description"));
    break;

  case "update":
    update_request($request["id"], array("answer" => $_POST["answer"]));
    foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
      delete_subsidy($subsidy["id"]);
    }
    foreach ($_POST["subsidies"] as $subsidy) {
      create_subsidy($subsidy["budget"], $request["id"], $subsidy["requested_amount"], $subsidy["optional_values"]);
    }
    $_SESSION["notice"][] = "Ta demande de subvention a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "review":
    $request_info = select_request($request["id"], array("id", "budget", "answer", "sending_date", "wave", "state"));
    $request_info["wave"] = select_wave($request_info["wave"], array("id", "binet", "term", "state"));
    $current_binet = select_binet($binet, array("id", "name", "description", "current_term", "subsidy_provider", "subsidy_steps"));
    $current_binet = array_merge(select_term_binet($current_binet["id"]."/".$current_binet["current_term"], array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state")), $current_binet);
    $previous_binet = select_term_binet($current_binet["id"]."/".($current_binet["current_term"] - 1), array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state"));
    $existing_subsidies = get_subsidized_amount_between($current_binet["id"]."/".$current_binet["current_term"], $request_info["wave"]["binet"]);
    $previous_subsidies = get_subsidized_amount_between($current_binet["id"]."/".($current_binet["current_term"] -  1), $request_info["wave"]["binet"]);
    break;

  case "grant":
    foreach ($_POST as $subsidy => $values) {
      update_subsidy($subsidy, $values);
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
    delete_request($request["id"]);
    $_SESSION["notice"][] = "Ta demande de subvention a été supprimée de tes brouillons.";
    redirect_to_path(path("", "request", "", binet_prefix($binet, $term)));
    break;

  case "send":
    send_request($request["id"]);
    $_SESSION["notice"][] = "Ta demande de subvention a été envoyée avec succès.";
    redirect_to_path(path("", "request", "", binet_prefix($binet, $term)));
    break;

  case "edit_converted_amount":
    break;

  case "set_converted_amount":
    foreach ($_POST as $subsidy => $converted_amount) {
      update_subsidy($subsidy, array("converted_amount" => $converted_amount));
    }
    $_SESSION["notice"][] = "Le montant débloqué de la demande de subvention a été mis à jour.";
    if (isset($_SESSION["return_to"])) {
      $return_to = $_SESSION["return_to"];
      unset($_SESSION["return_to"]);
      redirect_to_path($return_to);
    } else {
      redirect_to_action("show");
    }
    break;

  default:
    header_if(true, 403);
    exit;
  }
