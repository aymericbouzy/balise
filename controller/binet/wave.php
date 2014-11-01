<?php

  function not_published() {
    header_if(select_wave($_GET["wave"], array("published"))["published"], 403);
  }

  function subsidy_provider() {
    header_if(select_binet($_GET["binet"], array("subsidy_provider"))["subsidy_provider"], 401);
  }

  subsidy_provider();
  before_action("check_entry", array("show", "edit", "update", "publish"), array("model_name" => "wave", "binet" => $_GET["binet"], "term" => $_GET["term"]));
  before_action("member_binet_term", array("new", "create", "edit", "update", "publish"));
  before_action("not_published", array("publish"));


  switch ($_GET["action"]) {

  case "index":
    break;

  case "new":
    break;

  case "create":
    break;

  case "show":
    break;

  case "edit":
    break;

  case "update":
    break;

  case "publish":
    break;

  default:
    header_if(true, 403);
    exit;
  }
