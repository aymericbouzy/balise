<?php

  include "base.php";

  if (in_array($_GET["action"], array("new", "create", "change_term", "deactivate", "set_subsidy_provider")) && !status_binet_admin($KES_ID)) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
  }

  switch ($_GET["action"]) {
  case "index":
    break;
  case "new":
    break;
  case "create":
    break;
  case "edit":
    break;
  case "update":
    break;
  case "set_subsidy_provider":
    break;
  case "show":
    break;
  case "change_term":
    break;
  case "deactivate":
    break;
  }
