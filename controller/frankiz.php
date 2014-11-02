<?php

  switch ($_GET["action"]) {

  case "login":
    $_SESSION["notice"] = "Tu t'es connecté avec succès.";
    break;

  case "logout":
    $_SESSION["notice"] = "Tu t'es déconnecté avec succès.";
    break;

  default:
    header_if(true, 403);
    exit;
  }
