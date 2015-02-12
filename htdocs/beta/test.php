<?php

  include "global/initialisation.php";

  $term = select_term_binet("8/2014", array("subsidized_amount_used"));
  var_dump($term["subsidized_amount_used"]);
