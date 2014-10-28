<?php

  if (!validate_input(array("action"))) {
    header("HTTP/1.1 400 Bad Request");
    exit;
  }
