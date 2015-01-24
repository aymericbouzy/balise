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
  before_action("check_entry", array("show", "edit", "update", "publish"), array("model_name" => "wave", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "publish"));
  before_action("check_form_input", array("create", "update"), array(
    "model_name" => "wave",
    "str_fields" => array(array("submission_date", MAX_DATE_LENGTH), array("expiry_date", MAX_DATE_LENGTH)),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "request", $_GET["action"] == "update" ? $wave["id"] : "", binet_prefix($binet, $term))
  ));
  before_action("not_published", array("publish"));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  $form_fields = array("submission_date", "expiry_date");

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    $wave = initialise_for_form_from_session($form_fields, "wave");
    break;

  case "create":
    create_wave($binet, $term, $submission_date, $expiry_date);
    $_SESSION["notice"][] = "Une nouvelle vague de subvention a été ouverte.";
    redirect_to_action("show");
    break;

  case "show":
    $wave = select_wave($wave, array("id", "submission_date", "expiry_date", "published", "binet", "term"));
    break;

  case "edit":
    function wave_to_form_fields($wave) {
      return $wave;
    }
    $wave = set_editable_entry_for_form("wave", $wave["id"], $form_fields);
    break;

  case "update":
    update_wave($wave["id"], $_POST);
    $_SESSION["notice"][] = "La vague de subventions a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "publish":
    publish_wave($wave["id"]);
    $_SESSION["notice"][] = "Les attributions de la vague de subvention ont été publiées avec succès.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
