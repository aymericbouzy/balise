<?php

  include "global/initialisation.php";

  $test = mail("aymeric.bouzy@gmail.com", "test", "test");
  var_dump($test);
