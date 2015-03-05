<?php
  foreach ($budgets as $budget) {
    $budget = select_budget($budget["id"], array("label", "id"))
    echo form_input($budget["label"], "budget_".$budget["id"], $form);
  }
  echo form_submit_button("Transférer les budgets");
