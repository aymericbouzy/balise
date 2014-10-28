<?php

  include "../base.php";
  if (!validate_input(array("binet", "term"))) {
    header("HTTP/1.1 400 Bad Request");
    exit;
  }
