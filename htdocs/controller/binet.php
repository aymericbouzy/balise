<?php

  function check_unique_clean_name() {
    $binets = select_tags(array("clean_name" => clean_string($_POST["name"])));
    if (!empty($binets)) {
      $_SESSION["binet"]["errors"][] = "name";
      $_SESSION["error"][] = "Ce nom de binet est déjà utilisé par le binet ".pretty_binet($binets[0]["id"]).". Veuillez en choisir un autre.";
    }
  }

  before_action("check_csrf_post", array("update", "create", "set_term"));
  before_action("check_csrf_get", array("delete", "set_subsidy_provider", "deactivate"));
  before_action(
    "check_entry",
    array("edit", "update", "set_subsidy_provider", "show", "change_term", "set_term", "deactivate"),
    array("model_name" => "binet")
  );
  before_action("current_kessier", array("new", "create", "set_term", "change_term", "deactivate", "set_subsidy_provider", "admin"));
  before_action("check_editing_rights", array("edit", "update"));
  before_action("check_form_input", array("create"), array(
    "model_name" => "binet",
    "str_fields" => array(array("name", 30), array("description", 10000)),
    "other_fields" => array(array("name", "check_unique_clean_name")),
    "int_fields" => array(array("current_term", MAX_TERM)),
    "redirect_to" => path("new", "binet", "", binet_prefix($binet, $term))
  ));
  before_action("check_form_input", array("update"), array(
    "model_name" => "binet",
    "str_fields" => array(array("description", 10000), array("subsidy_steps", 50000)),
    "redirect_to" => path("edit", "binet", $binet, binet_prefix($binet, $term)),
    "optionnal" => array("description", "subsidy_steps")
  ));
  before_action("check_form_input", array("set_term"), array(
    "model_name" => "binet",
    "str_fields" => array(array("term", MAX_TERM)),
    "redirect_to" => path("change_term", "binet", $binet, binet_prefix($binet, $term))
  ));
  before_action("generate_csrf_token", array("new", "edit", "change_term"));

  $binet_form_fields = array("name", "term");
  $description_form_fields = array("name", "description", "subsidy_steps");
  $term_form_fields = array("term");

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    $binet = initialise_for_form($binet_form_fields, $_SESSION["binet"]);
    break;

  case "create":
    $binet = create_binet($_POST["name"], $_POST["term"]);
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet)." a été créé avec succès.";
    redirect_to_action("show");
    break;

  case "edit":
    function binet_to_form_fields($binet) {
      return $binet;
    }
    $binet = set_editable_entry_for_form("binet", $binet, $description_form_fields);
    break;

  case "update":
    update_binet($binet["id"], $_POST);
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." a été mis à jour avec succès.";
    redirect_to_action("show");
    break;

  case "set_subsidy_provider":
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." est devenu un binet subventionneur.";
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "change_term":
    function binet_to_form_fields($binet) {
      $binet["term"] = $binet["current_term"];
      return $binet;
    }
    $binet = set_editable_entry_for_form("binet", $binet, $term_form_fields);
    break;

  case "set_term":
    change_term_binet($binet["id"], $_POST["term"]);
    $_SESSION["notice"][] = "Le mandat actuel du binet ".pretty_binet($binet["id"])." a été mis à jour.";
    redirect_to_action("show");
    break;

  case "deactivate":
    deactivate_binet($binet["id"]);
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." a été désactivé avec succès.";
    redirect_to_action("show");
    break;

  case "admin":
    break;

  default:
    header_if(true, 403);
    exit;
  }
