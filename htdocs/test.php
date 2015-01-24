<?php
  include "global/initialisation.php";

  var_dump(select_budgets(array("binet" => 1, "term" => 2012, "amount" => array(-234 > 0 ? ">" : "<", 0))));
