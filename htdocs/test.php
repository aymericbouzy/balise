<?php
  include "global/initialisation.php";

  $test = send_email(51, "Nouvelle vague de subventions", "new_wave", array("wave" => 1, "binet" => 1, "term" => 2012));

  var_dump($test);
