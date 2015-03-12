<?php

  function check_is_publishable() {
    header_if(!is_publishable($_GET["wave"]), 403);
  }

  function is_publishable($wave) {
    $wave = select_wave($wave, array("published", "requests_received", "requests_reviewed", "state"));
    return $wave["published"] != 1 && $wave["state"] == "deliberation" && $wave["requests_received"] == $wave["requests_reviewed"];
  }

  function subsidy_provider() {
    header_if(select_binet($_GET["binet"], array("subsidy_provider"))["subsidy_provider"], 401);
  }

  function is_openable($wave) {
    $wave = select_wave($wave, array("open", "submission_date"));
    return !$wave["open"] && $wave["submission_date"] > current_date();
  }

  function check_is_openable() {
    header_if(!is_openable($_GET["wave"]), 403);
  }

  function check_has_viewing_rights_if_rough_draft() {
    header_if(!has_viewing_rights($GLOBALS["binet"], $GLOBALS["term"]) && select_wave($_GET["wave"], array("state"))["state"] == "rough_draft", 401);
  }

  before_action("check_csrf_get", array("publish"));
  subsidy_provider();
  before_action("check_entry", array("show", "edit", "update", "publish", "open"), array("model_name" => "wave", "binet" => $binet, "term" => $term));
  before_action("check_editing_rights", array("new", "create", "edit", "update", "publish", "open"));
  before_action("check_has_viewing_rights_if_rough_draft", array("show"));
  before_action("create_form", array("new", "create", "edit", "update"), "wave");
  before_action("check_form", array("create", "update"), "wave");
  before_action("check_is_publishable", array("publish"));
  before_action("check_is_openable", array("open"));

  $form_fields = array("submission_date", "expiry_date", "question");

  switch ($_GET["action"]) {

  case "index":
    $waves = select_waves(array("binet" => $binet, "term" => $term), "submission_date");
    $waves_rough_drafts = select_waves(array("binet" => $binet, "term" => $term, "state" => "rough_draft"), "submission_date");
    break;

  case "new":
    $wave = initialise_for_form_from_session($form_fields, "wave");
    break;

  case "create":
    $wave["id"] = create_wave($binet, $term, $_POST);
    $_SESSION["notice"][] = "La vague de subvention a été créée. Elle apparait maintenant dans ton budget et tes brouillons. Les binets pourront faire leurs demandes de subventions une fois qu'elle sera ouverte.";
    redirect_to_action("show");
    break;

  case "open":
    open_wave($wave["id"]);
    $binets_per_student = array();
    foreach (select_binets() as $any_binet) {
      foreach (select_current_admins($any_binet["id"]) as $student) {
        $binets_per_student[$student["id"]][] = $any_binet["id"];
      }
    }
    foreach ($binets_per_student as $student => $binets) {
      send_email($student, "Nouvelle vague de subventions", "new_wave", array("wave" => $wave["id"], "binets" => $binets));
    }
    $_SESSION["notice"][] = "La vague de subvention est à présent ouverte. Un mail a été envoyé aux administrateurs de binets pour les prévenir.";
    redirect_to_action("show");
    break;

  case "show":
    $wave = select_wave($wave["id"], array("id", "submission_date", "expiry_date", "published", "binet", "term", "state", "amount", "requested_amount", "granted_amount", "used_amount", "description", "question", "explanation", "requests_received", "requests_reviewed","requests_accepted"));
    break;

  case "edit":
    function wave_to_form_fields($wave) {
      return $wave;
    }
    $wave = set_editable_entry_for_form("wave", $wave, $form_fields);
    break;

  case "update":
    update_wave($wave["id"], $_POST);
    $_SESSION["notice"][] = "La vague de subventions a été mise à jour avec succès.";
    redirect_to_action("show");
    break;

  case "publish":
    $wave = select_wave($wave["id"], array("id", "explanation"));
    if (is_empty($wave["explanation"])) {
      $_SESSION["warning"][] = "Tu n'as pas mis de texte publique d'explication des subventions accordées. Tu peux le rajouter en modifiant la vague.";
    }
    publish_wave($wave["id"]);
    $affected_operations = reset_kes_validation_for_operations_affected_by_wave($wave["id"]);
    $_SESSION["notice"][] = "Les attributions de la vague de subvention ont été publiées avec succès.";
    $requests_per_student = array();
    foreach (select_requests(array("wave" => $wave["id"])) as $request) {
      $request = select_request($request["id"], array("binet", "term", "id"));
      foreach (select_admins($request["binet"], $request["term"]) as $student) {
        $requests_per_student[$student["id"]][] = $request["id"];
      }
    }
    foreach ($requests_per_student as $student => $requests) {
      send_email($student, "Subventions accordées", "wave_published", array("wave" => $wave["id"], "requests" => $requests));
    }
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
