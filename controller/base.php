<?php

  if (!validate_input(array("action"))) {
    header("HTTP/1.1 400 Bad Request");
    exit;
  }
  if (!validate_input(array("student"), "session") && $_GET["action"] != "login") {
    header("Location: http://".$HOST."/".path("login"));
    exit;
  }
