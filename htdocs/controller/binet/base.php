<?php

  check_binet_term(); // defines $binet and $term
  check_viewing_rights();

  include CONTROLLER_PATH."binet/".$_GET["controller"].".php";
