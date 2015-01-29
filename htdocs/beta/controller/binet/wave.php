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
    "str_fields" => array(array("submission_date", MAX_DATE_LENGTH), array("expiry_date", MAX_DATE_LENGTH), array("question", MAX_TEXT_LENGTH)),
    "redirect_to" => path($_GET["action"] == "update" ? "edit" : "new", "request", $_GET["action"] == "update" ? $wave["id"] : "", binet_prefix($binet, $term))
  ));
  before_action("not_published", array("publish"));
  before_action("generate_csrf_token", array("new", "edit", "show"));

  $form_fields = array("submission_date", "expiry_date", "question");

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    $wave = initialise_for_form_from_session($form_fields, "wave");
    break;

  case "create":
    $wave["id"] = create_wave($binet, $term, $_POST["submission_date"], $_POST["expiry_date"], $_POST["question"]);
    $_SESSION["notice"][] = "Une nouvelle vague de subvention a été ouverte.";
    $binets_per_student = array();
    foreach (select_binets() as $any_binet) {
      foreach (select_current_admins($any_binet["id"]) as $student) {
        $binets_per_student[$student["id"]][] = $any_binet["id"];
      }
    }
    foreach ($binets_per_student as $student => $binets) {
      send_email($student, "Nouvelle vague de subventions", "new_wave", array("wave" => $wave["id"], "binets" => $binets));
    }
    redirect_to_action("show");
    break;

  case "show":
    $wave = select_wave($wave["id"], array("id", "submission_date", "expiry_date", "published", "binet", "term", "state"));
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
    publish_wave($wave["id"]);
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
