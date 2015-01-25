<form role="form" id="budget" action="/<?php echo path($form_action, "budget", $form_action == "create" ? "" : $budget["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php
    if ($_GET["action"] == "new") {
      echo form_group_checkbox("Dépense :", "sign", $budget, "budget");
    }
  ?>
  <?php echo form_group_text("Nom :", "label", $budget, "budget"); ?>
  <?php echo form_group_text("Tags (séparés par des ';') :", "tags_string", $budget, "budget"); ?>
  <?php echo form_group_text("Montant prévisionnel :", "amount", $budget, "budget"); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>