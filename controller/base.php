<?php

  include "../global/initialisation.php";

  header_if(!validate_input(array("action")), 400);
  
  if (!validate_input(array("student"), "session") && $_GET["action"] != "login") {
    header("Location: ".$SCHEME."://".$HOST."/".path("login"));
    exit;
  }
