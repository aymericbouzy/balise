<?php

  include "global/initialisation.php";

  try {
    ob_start();
    include "controller/base.php";
    echo ob_get_clean();
  } catch (Exception $e) {
    ob_get_clean();
    header_if(true, 500);
  }
