<?php

  check_binet_term();
  $viewing_rights_exceptions[] = array("show", "wave");
  $viewing_rights_exceptions[] = array("index", "wave");
  $viewing_rights_exceptions[] = array("show", "request");
  $viewing_rights_exceptions[] = array("show", "operation");
  if (!in_array(array($_GET["action"], $_GET["controller"]), $viewing_rights_exceptions)) {
    check_viewing_rights();
  }

  include CONTROLLER_PATH."binet/".$_GET["controller"].".php";
