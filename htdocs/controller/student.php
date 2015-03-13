<?php

  before_action("check_entry", array("show"), array("model_name" => "student"));

  switch ($_GET["action"]) {

  case "show":
    $student = select_student($student["id"], array("id", "name", "email"));
    break;

  default:
    header_if(true, 403);
    exit;
  }
