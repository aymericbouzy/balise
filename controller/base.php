<?php

  correct_action();
  if (!validate_input(array("student"), "session") && $_GET["action"] != "login") {
    header("Location: ".$SCHEME."://".$HOST."/".path("login"));
    exit;
  }
