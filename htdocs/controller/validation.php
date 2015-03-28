<?php

  before_action("current_kessier", array("index"));

  switch ($_GET["action"]) {

  case "index":
    $pending_validations_operations_kes = kes_pending_validations_operations();
    break;

  default:
    header_if(true, 403);
    exit;
  }
