<?php

  function not_published() {
    header_if(select_wave($_GET["wave"], array("published"))["published"], 403);
  }

  function subsidy_provider() {
    header_if(select_binet($_GET["binet"], array("subsidy_provider"))["subsidy_provider"], 401);
  }

  subsidy_provider();
  before_action("check_entry", array("show", "edit", "update", "publish"), array("model_name" => "wave", "binet" => $binet["id"], "term" => $term));
  before_action("member_binet_term", array("new", "create", "edit", "update", "publish"));
  before_action("not_published", array("publish"));


  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    $_SESSION["notice"] = "Une nouvelle vague de subvention a été ouverte.";
    redirect_to(array("action" => "show"));
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    $_SESSION["notice"] = "La vague de subventions a été mise à jour avec succès.";
    redirect_to(array("action" => "show"));
    break;

  case "publish":
    $_SESSION["notice"] = "Les attributions de la vague de subvention ont été publiées avec succès.";
    redirect_to(array("action" => "show"));
    break;

  default:
    header_if(true, 403);
    exit;
  }
