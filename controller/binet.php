<?php

  if (validate_input(array("binet", "term"), array())) {
    $binets = select_binets(array("clean_name" => $_GET["binet"]));
    if (!empty($binets)) {
      $binet = $binets[0];
    }
  }
