<?php
  include "global/initialisation.php";

  $criteria = array("kes_validation_by" => NULL, "binet_validation_by" => NULL, "binet" => 1, "term" => 2012);
  var_dump($criteria);
  // foreach ($criteria as $key => $value) {
  //   var_dump($key, $value);
  // }
  var_dump(pending_validations_operations(1, 2012));
