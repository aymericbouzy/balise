<?php

  switch ($_GET["action"]) {

  case "index":
    $waves = select_waves();
    break;

  default:
    header_if(true, 403);
    exit;
  }
