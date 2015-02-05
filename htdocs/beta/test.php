<?php
  include "global/initialisation.php";

  $regex = "/^([0-9]{2})\/([0-9]{2})\/(2[0-9]{3})$/";
  if (preg_does_match($regex, "20-02-2015") {
    $_POST[$field[0]] = preg_replace($regex, "$3-$2-$1", $_POST[$field[0]]);
  }
  var_dump($_POST[$field[0]]);
