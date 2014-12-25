<form role="form" id="operation" action="/<?php echo path($form_action, "operation", $form_action == "create" ? "" : $operation["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php echo form_group_text("Description :", "comment", $operation, "operation"); ?>
  <?php echo form_group_text("Référence de facture :", "bill", $operation, "operation"); ?>
  <?php echo form_group_text("Référence de paiement :", "reference", $operation, "operation"); ?>
  <?php echo form_group_text("Montant :", "amount", $operation, "operation"); ?>
  <?php echo form_group_checkbox("Dépense", "sign", $operation, "operation"); ?>
  <?php echo form_group_text("Type de transaction :", "type", $operation, "operation"); ?>
  <?php echo form_group_text("Payé par :", "paid_by", $operation, "operation"); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
