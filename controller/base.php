<?php

  include "../global/initialisation.php";

  header_if(!validate_input(array("action")), 400);
  header_if(!validate_input(array("controller")), 400);
  if (isset($_GET["prefix"])) {
    header_if(!validate_input(array("prefix")), 400);
    header_if(!in_array($_GET["prefix"], array("binet")));
    $full_controller = $_GET["prefix"]."/".$_GET["controller"];
  } else {
    $full_controller = $_GET["controller"];
  }
  header_if(!in_array($full_controller, array("binet", "frankiz", "operation", "tag", "wave", "binet/admin", "binet/budget", "binet/operation", "binet/request", "binet/wave")), 404);

  if (!validate_input(array("student"), "session") && ($_GET["controller"] != "frankiz" || $_GET["action"] != "login") {
    redirect_to(path("login"));
  }

  include $CONTROLLER_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/base.php" : $_GET["controller"].".php");
