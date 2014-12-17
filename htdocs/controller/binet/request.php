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

  function sent_and_not_published() {
    $request = select_request($GLOBALS["request"], array("sent", "wave"));
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
    $req->bindValue(':request', $GLOBALS["request"], PDO::PARAM_INT);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    return !empty($req->fetch());
  }

  before_action("check_csrf_post", array("update", "create", "grant"));
  before_action("check_csrf_get", array("delete", "send"));
  before_action("check_entry", array("show", "edit", "update", "delete", "send", "review", "grant"), array("model_name" => "request", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "delete", "send"));
  before_action("check_granting_rights", array("review", "grant"));
  $requested_amount_array = array_map("adds_amount_prefix", budgets_involved());
  $purpose_array = array_map("adds_purpose_prefix", budgets_involved());
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "request",
    "str_fields" => array_merge(array(array("answer", 100000)), array_map("adds_max_length_purpose", $purpose_array)),
    "amount_fields" => array_map("adds_max_amount", $requested_amount_array),
    "other_fields" => array(array("wave", "exists_wave")),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "request", $_GET["action"] == "update" ? $request["id"] : "", binet_prefix($binet, $term))
  ));
  $granted_amount_array = array_map("adds_amount_prefix", subsidies_involved());
  $explanation_array = array_map("adds_purpose_prefix", subsidies_involved());
  before_action("check_form_input", array("grant"), array(
    "model_name" => "request",
    "str_fields" => $explanation_array,
    "amount_fields" => $granted_amount_array,
    "redirect_to" => path("review", "request", $request["id"], binet_prefix($binet, $term))
  ));
  before_action("not_sent", array("send", "edit", "update", "delete"));
  before_action("sent_and_not_published", array("review", "grant"));
  before_action("generate_csrf_token", array("new", "edit", "show", "review"));

  $edit_form_fields = array_merge(array("answer", "wave"), $requested_amount_array, $purpose_array);
  $review_form_fields = array_merge($granted_amount_array, $explanation_array);

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
    // $request = create_request();
    // foreach ($_POST as $field => $value) {
    //   $field_elements = explode("_", $field);
    //   if ($field_elements[0]."_" == amount_prefix && $value > 0) {
    //     create_subsidy(
    //       $field_elements[1],
    //       $request,
    //       $value,
    //       array("purpose" => $_POST[adds_purpose_prefix(array("id" => $field_elements[1]))])
    //     );
    //   }
    // }
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
    $request = set_editable_entry_for_form("request", $request["id"], $edit_form_fields);
    break;

  case "update":
    $_SESSION["notice"][] = "Ta demande de subvention a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "review":
    function request_to_form_fields($request) {
      foreach (subsidies_involved() as $subsidy) {
        $subsidy = select_subsidy($subsidy["id"], array("granted_amount", "explanation"));
        $request[add_amount_prefix($subsidy)] = $subsidy["granted_amount"];
        $request[add_purpose_prefix($subsidy)] = $subsidy["explanation"];
      }
      return $request;
    }
    $request = set_editable_entry_for_form("request", $request["id"], $review_form_fields);
    break;

  case "grant":
    // TODO : check fields of POST can only be in $rewiew_form_fields
    foreach ($_POST as $field => $value) {
      $field_elements = explode("_", $field);
      switch ($field_elements[0]."_") {
        case amount_prefix:
          update_subsidy($field_elements[1], array("granted_amount" => $value));
          break;
        case purpose_prefix:
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
    $_SESSION["notice"][] = "Ta demande de subvention a été envoyée avec succès.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
