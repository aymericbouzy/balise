<?php

  include "global/initialisation.php";

  var_dump(select_term_binet("6/2013", array("subsidized_amount_used", "subsidized_amount_granted", "subsidized_amount_requested")));
  echo "<br>Budgets :<br>";
  foreach (select_budgets(array("binet" => 6, "term" => 2013)) as $budget) {
    var_dump(select_budget($budget["id"], array("id", "subsidized_amount_requested", "subsidized_amount_granted", "subsidized_amount_used")));
    echo "<br>";
  }
  echo "<br>Operations du budget fontaine de chocolat : <br>";
  foreach (select_operations_budget(10) as $operation) {
    var_dump(select_operation($operation["id"], array("state", "subsidized_amount_requested", "subsidized_amount_granted", "subsidized_amount_used")));
    echo "<br>";
  }
