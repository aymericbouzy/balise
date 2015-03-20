<?php

  function check_is_deactivated() {
    $current_term = current_term($GLOBALS["binet"]["id"]);
    header_if(!is_empty($current_term), 403);
  }

  function check_is_activated() {
    $current_term = current_term($GLOBALS["binet"]["id"]);
    header_if(is_empty($current_term), 403);
  }

  function check_editing_rights_or_current_kessier() {
    header_if(!has_editing_rights($GLOBALS["binet"]["id"], current_term($GLOBALS["binet"]["id"])) && !is_current_kessier(), 401);
  }

  before_action("check_csrf_get", array("delete", "switch_subsidy_provider", "deactivate", "power_transfer"));
  before_action(
    "check_entry",
    array("edit", "update", "switch_subsidy_provider", "show", "change_term", "reactivate", "deactivate", "power_transfer"),
    array("model_name" => "binet")
  );
  before_action("check_is_activated", array("power_transfer", "deactivate"));
  before_action("check_is_deactivated", array("reactivate"));
  before_action("current_kessier", array("new", "create", "power_transfer", "change_term", "deactivate", "reactivate", "switch_subsidy_provider"));
  before_action("check_editing_rights_or_current_kessier", array("edit", "update"));
  before_action("create_form", array("new", "create", "edit", "update", "change_term", "reactivate"), "binet");
  before_action("check_form", array("create", "update", "reactivate"), "binet");

  $term_form_fields = array("term");

  switch ($_GET["action"]) {

  case "index":
    $binets = select_binets(array("current_term" => array("IS", "NOT NULL")), "name");
    break;

  case "new":
    break;

  case "create":
    $binet["id"] = create_binet($_POST["name"], $_POST["term"]);
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." a été créé avec succès.";
    redirect_to_action("show");
    break;

  case "edit":
    break;

  case "update":
    update_binet($binet["id"], $_POST);
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." a été mis à jour avec succès.";
    redirect_to_action("show");
    break;

  case "switch_subsidy_provider":
    // TODO add email
    if (select_binet($binet["id"], array("subsidy_provider"))["subsidy_provider"]) {
      unset_subsidy_provider($binet["id"]);
      $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." n'est à présent plus un binet subventionneur.";
    } else {
      set_subsidy_provider($binet["id"]);
      $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." est devenu un binet subventionneur.";
    }
    redirect_to_action("show");
    break;

  case "show":
    $binet = select_binet($binet["id"], array("id", "name", "description", "current_term", "subsidy_provider", "subsidy_steps"));
    $binet = array_merge(select_term_binet(term_id($binet["id"], $binet["current_term"]), array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested", "real_spending", "real_income", "real_balance", "expected_spending", "expected_income", "expected_balance", "state")), $binet);
    foreach (select_waves(array("binet" => $binet["id"]), "submission_date") as $wave) {
      $waves[] = select_wave($wave["id"], array("id", "binet", "term", "submission_date", "expiry_date", "published"));
    }
    if (is_empty($binet["current_term"])) {
      $binet["state"] = "grey";
    }
    break;

  case "change_term":
    break;

  case "power_transfer":
    change_term_binet($binet["id"], current_term($binet["id"]) + 1);
    $_SESSION["notice"][] = "La passation du binet ".pretty_binet($binet["id"])." s'est déroulée avec succès !";
    redirect_to_action("show");
    break;

  case "reactivate":
    change_term_binet($binet["id"], $_POST["term"]);
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." existe à nouveau avec la promotion ".$_POST["term"]." !";
    redirect_to_action("show");
    break;

  case "deactivate":
    deactivate_binet($binet["id"]);
    $_SESSION["notice"][] = "Le binet ".pretty_binet($binet["id"])." a été désactivé avec succès.";
    redirect_to_action("show");
    break;

  default:
    header_if(true, 403);
    exit;
  }
