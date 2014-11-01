<?php

  function not_sent() {
    header_if(select_request($_GET["request"], array("sent"))["sent"], 403);
  }

  before_action("check_entry", array("show", "edit", "update", "delete", "send"), array("model_name" => "request", "binet" => $_GET["binet"], "term" => $_GET["term"]));
  before_action("member_binet_term", array("new", "create", "edit", "update", "delete", "send"));
  before_action("not_sent", array("send"));

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

  case "delete":
    break;

  case "send":
    break;

  default:
    header_if(true, 403);
    exit;
  }
