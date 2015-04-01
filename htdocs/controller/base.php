<?php

  header_if(!validate_input(array("action", "controller"), array("tags")), 400);

  if (isset($_GET["prefix"])) {
    header_if(!validate_input(array("prefix")), 400);
    header_if(!in_array($_GET["prefix"], array("binet")), 404);
    $full_controller = $_GET["prefix"]."/".$_GET["controller"];
  } else {
    $full_controller = $_GET["controller"];
  }
  header_if(!in_array($full_controller, array("binet", "home", "operation", "tag", "wave", "student", "binet/member", "binet/budget", "binet/operation", "binet/request", "validation", "binet/wave", "error")), 404);

  $query_array = compute_query_array();

  if (!($_GET["controller"] == "error" || ($_GET["controller"] == "home" && (in_array($_GET["action"], array("login", "chose_identity", "welcome")))))) {
    if (!connected_student()) {
      $_SESSION["redirect_to_after_connection"] = $_SERVER["REQUEST_URI"]; // original URL requested by the user
      redirect_to_path(path("login", "home"));
    }
  }

  create_form("bug_report");

  if ($_GET["controller"] != "error") {
    include CONTROLLER_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/base.php" : $_GET["controller"].".php");
  } else {
    header_if($_GET["action"] == "unknown_url", 400);
  }

  if (!(STATE == "development" && ob_get_length() != 0)) {
    include LAYOUT_PATH."application.php";
  }
