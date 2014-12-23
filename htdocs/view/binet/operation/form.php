<form role="form" id="operation" action="<?php echo path($form_action, "operation", $form_action == "create" ? "" : $operation["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php echo form_group_text("Description :", "comment", $operation); ?>
  <?php echo form_group_text("Référence de facture :", "bill", $operation); ?>
  <?php echo form_group_text("Référence de paiement :", "reference", $operation); ?>
  <?php echo form_group_text("Montant :", "reference", $operation); ?>
  <?php echo form_group_checkbox("Dépense :", "sign", $operation); ?>
  <?php echo form_group_text("Type de transaction :", "type", $operation); ?>
  <?php echo form_group_text("Payé par :", "paid_by", $operation); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
