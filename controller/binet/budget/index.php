<?php

  if (isset($_GET["binet"])) {
    $binet = select_binet($_GET["binet"]);
    $term = isset($_GET["term"]) ? $_GET["term"] : $binet["current_term"];

    if (status_admin_binet($binet["id"], $term)) {

    } else {
      include $VIEW_PATH."/error/denied.php";
    }
  }
