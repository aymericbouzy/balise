<?php

  define("amount_prefix", "amount_");

  function adds_amount_prefix($budget) {
    return amount_prefix.$budget["id"];
  }

  function subsidy_amount_requested_array() {
    return array_map(select_budgets(array("binet" => $GLOBALS["binet"]["id"], "term" => $GLOBALS["term"])), "adds_amount_prefix");
  }

  function adds_max_amount($amount) {
    return array($amount, MAX_AMOUNT);
  }

  function not_sent() {
    header_if(select_request($_GET["request"], array("sent"))["sent"], 403);
  }

  before_action("check_csrf_post", array("update", "create"));
  before_action("check_csrf_get", array("delete", "send"));
  before_action("check_entry", array("show", "edit", "update", "delete", "send"), array("model_name" => "request", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "create", "edit", "update", "delete", "send"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "request",
    "str_fields" => array(array("answer", 100000)),
    "amount_fields" => array_map("adds_max_amount", subsidy_amount_requested_array()),
    "other_fields" => array(array("wave", "exists_wave"), array("paid_by", "exists_student")),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "request", $_GET["action"] == "update" ? $request["id"] : "", binet_prefix($binet["id"], $term)),
    "optionnal" => array()
  ));
  before_action("not_sent", array("send", "edit", "update", "delete"));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "Ta demande de subvention a été sauvegardée dans tes brouillons.";
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "Ta demande de subvention a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "delete":
    $_SESSION["notice"] = "Ta demande de subvention a été supprimée de tes brouillons.";
    redirect_to_action("index");
    break;

  case "send":
    $_SESSION["notice"] = "Ta demande de subvention a été envoyée avec succès.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
