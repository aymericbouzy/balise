<?php

  function check_unique_clean_name() {
    $binets = select_tags(array("clean_name" => clean_string($_POST["name"])));
    if (!empty($binets)) {
      $_SESSION["binet"]["errors"][] = "name";
      $_SESSION["error"][] = "Ce nom de binet (ou un très proche) est déjà utilisé. Veuillez en choisir un autre.";
    }
  }

  before_action("check_csrf_post", array("update", "create", "set_term"));
  before_action("check_csrf_get", array("delete", "set_subsidy_provider", "deactivate"));
  before_action(
    "check_entry",
    array("edit", "update", "set_subsidy_provider", "show", "change_term", "set_term", "deactivate", "validation"),
    array("model_name" => "binet")
  );
  before_action("kessier", array("new", "create", "set_term", "change_term", "deactivate", "set_subsidy_provider", "admin"));
  before_action("member_binet_current_term", array("edit", "update", "validation"));
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
    "str_fields" => array(array("current_term", MAX_TERM)),
    "redirect_to" => path("change_term", "binet", $binet, binet_prefix($binet, $term))
  ));
  before_action("generate_csrf_token", array("new", "edit", "change_term"));

  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"][] = "Le binet ".$binet["name"]." a été créé avec succès.";
    redirect_to_action("show");
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"][] = "Le binet ".$binet["name"]." a été mis à jour avec succès.";
    redirect_to_action("show");
    break;

  case "set_subsidy_provider":
    $_SESSION["notice"][] = "Le binet ".$binet["name"]." est devenu un binet subventionneur.";
    redirect_to_action("show");
    break;

  case "show":
    break;

  case "change_term":
    break;

  case "set_term":
    $_SESSION["notice"][] = "Le mandat actuel du binet ".$binet["name"]." a été mis à jour.";
    redirect_to_action("show");
    break;

  case "deactivate":
    $_SESSION["notice"][] = "Le binet ".$binet["name"]." a été désactivé avec succès.";
    redirect_to_action("show");
    break;

  case "validation":
    $pending_validations_operations = pending_validations_operations($binet, $term);
    if ($binet == KES_ID) {
      $pending_validations_operations_kes = pending_validations_operations_kes();
    }
    break;

  case "admin":
    break;

  default:
    header_if(true, 403);
    exit;
  }
