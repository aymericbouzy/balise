<?php

  include "../base.php";
  if (!validate_input(array("binet", "term"))) {
    header("HTTP/1.1 400 Bad Request");
    exit;
  }
  $binets = select_binets(array("clean_name" => $_GET["binet"]));
  if (empty($binets)) {
    header("HTTP/1.1 404 Not Found");
    exit;
  }
  $binet = $binets[0]["id"];
  if (!status_binet_admin($binet, $_GET["term"]) && !status_binet_admin($KES_ID) && !watching_subsidy_requester($binet)) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
  }
