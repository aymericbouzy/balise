<form>
<?php  foreach ($budgets as $budget) {
      $budget = select_budget($budget,array("label","id"))
      echo form_group_checkbox($budget["label"],"?",true, $form);
  }
  echo form_submit_button("Transférer les budgets"); ?>
</form>
