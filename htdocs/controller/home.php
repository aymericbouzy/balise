<?php

  switch ($_GET["action"]) {

  case "login":
    $_SESSION["notice"] = "Tu t'es connecté avec succès.";
    redirect_to_action("");
    break;

  case "logout":
    $_SESSION["notice"] = "Tu t'es déconnecté avec succès.";
    redirect_to_action("welcome");
    break;

  case "index":
    break;

  case "welcome":
    break;

  default:
    header_if(true, 403);
    exit;
  }
