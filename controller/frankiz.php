<?php

  include "base.php";

  switch ($_GET["action"]) {

  case "login":
    break;

  case "logout":
    break;

  default:
    header_if(true, 403);
    exit;
  }
