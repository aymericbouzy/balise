<?php

  check_binet_term(); // defines $binet and $term
  watcher_binet_term();

  include CONTROLLER_PATH."binet/".$_GET["controller"].".php";
