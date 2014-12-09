<?php

  function not_published() {
    header_if(select_wave($_GET["wave"], array("published"))["published"], 403);
  }

  function subsidy_provider() {
    header_if(select_binet($_GET["binet"], array("subsidy_provider"))["subsidy_provider"], 401);
  }

  before_action("check_csrf_post", array("update", "create"));
  before_action("check_csrf_get", array("publish"));
  subsidy_provider();
  before_action("check_entry", array("show", "edit", "update", "publish"), array("model_name" => "wave", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "create", "edit", "update", "publish"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "wave",
    "str_fields" => array(array("submission_date", MAX_DATE_LENGTH), array("expiry_date", MAX_DATE_LENGTH)),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "request", $_GET["action"] == "update" ? $request["id"] : "", binet_prefix($binet["id"], $term))
  ));
  before_action("not_published", array("publish"));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "Une nouvelle vague de subvention a été ouverte.";
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "La vague de subventions a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "publish":
    $_SESSION["notice"] = "Les attributions de la vague de subvention ont été publiées avec succès.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
