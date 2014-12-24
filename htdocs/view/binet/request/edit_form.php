<form role="form" id="request" action="/<?php echo path($form_action, "request", $form_action == "create" ? "" : $request["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php
    foreach ($budgets_involved as $budget) {
      echo form_group_text(pretty_budget("Montant pour le budget ".$budget["id"])." :", adds_amount_prefix($budget), $request);
      echo form_group_text(pretty_budget("Raison de la demande pour le budget ".$budget["id"])." :", adds_purpose_prefix($budget), $request);
    }
  ?>
  <?php echo form_group_text($question, "answer", $request); ?>
  <?php echo form_group_hidden("wave", $request); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
