<?php

  include "../global/initialisation.php";

  header_if(!validate_input(array("action", "controller"), array("tags")), 400);
  if (isset($_GET["prefix"])) {
    header_if(!validate_input(array("prefix")), 400);
    header_if(!in_array($_GET["prefix"], array("binet")));
    $full_controller = $_GET["prefix"]."/".$_GET["controller"];
  } else {
    $full_controller = $_GET["controller"];
  }
  header_if(!in_array($full_controller, array("binet", "frankiz", "operation", "tag", "wave", "binet/admin", "binet/budget", "binet/operation", "binet/request", "binet/wave")), 400);

  $query_array = compute_query_array();

  if (!validate_input(array("student"), "session") && ($_GET["controller"] != "frankiz" || $_GET["action"] != "login") {
    redirect_to_path(path("login"));
  } else {
    $current_student = select_student($_SESSION["student"], array("full_name"))
  }

  include $CONTROLLER_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/base.php" : $_GET["controller"].".php");

  include $LAYOUT_PATH."application.php";
