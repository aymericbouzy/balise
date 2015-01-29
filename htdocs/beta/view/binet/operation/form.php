<form role="form" id="operation" action="/<?php echo path($form_action, "operation", $form_action == "create" ? "" : $operation["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php echo form_group_text("Description :", "comment", $operation, "operation"); ?>
  <?php echo form_group_text("Référence de facture :", "bill", $operation, "operation"); ?>
  <?php echo form_group_text("Référence de paiement :", "reference", $operation, "operation"); ?>
  <?php echo form_group_text("Montant :", "amount", $operation, "operation"); ?>
  <?php echo form_group_checkbox("Dépense", "sign", $operation, "operation"); ?>
  <?php echo form_group_select("Type de transaction :", "type", option_array(select_operation_types(), "id", "name", "operation_type"), $operation, "operation"); ?>
  <?php echo form_group_select("Payé par :", "paid_by", option_array(select_students(), "id", "name", "student"), $operation, "operation"); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
