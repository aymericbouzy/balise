<?php

  before_action("member_binet_current_term", array("index"));

  switch ($_GET["action"]) {

  case "index":
    $pending_validations_operations = pending_validations_operations($binet, $term);
    if ($binet == KES_ID) {
      $pending_validations_operations_kes = kes_pending_validations_operations();
    }
    break;

  default:
    header_if(true, 403);
    exit;
  }
