<?php

  header_if(!validate_input(array("action", "controller"), array("tags")), 400);

  if (isset($_GET["prefix"])) {
    header_if(!validate_input(array("prefix")), 400);
    header_if(!in_array($_GET["prefix"], array("binet")), 404);
    $full_controller = $_GET["prefix"]."/".$_GET["controller"];
  } else {
    $full_controller = $_GET["controller"];
  }
  header_if(!in_array($full_controller, array("binet", "home", "operation", "tag", "wave", "binet/admin", "binet/budget", "binet/operation", "binet/request", "binet/validation", "binet/wave", "error")), 404);

  $query_array = compute_query_array();

  if (!($_GET["controller"] == "error" || ($_GET["controller"] == "home" && ($_GET["action"] == "login" || $_GET["action"] == "welcome")))) {
    $student = connected_student();
    if (!$student) {
      $_SESSION["redirect_to_after_connection"] = $_SERVER["REDIRECT_URL"]; // original URL requested by the user
      redirect_to_path(path("login", "home"));
    } else {
      $current_student = select_student($_SESSION["student"], array("full_name"));
    }
  }

  if ($_GET["controller"] != "error") {
    include CONTROLLER_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/base.php" : $_GET["controller"].".php");
  }

  if (!(STATE == "development" && headers_sent())) {
    include LAYOUT_PATH."application.php";
  }
