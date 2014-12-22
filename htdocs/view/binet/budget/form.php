<form role="form" id="budget" action="<?php echo path($form_action, "budget", "", binet_prefix($binet, $term)); ?>" method="post">
  <?php echo form_group_checkbox("Dépense :", "sign", $budget); ?>
  <?php echo form_group_text("Nom :", "label", $budget); ?>
  <?php echo form_group_text("Tags (séparés par des ';') :", "tags_string", $budget); ?>
  <?php echo form_group_text("Montant prévisionnel :", "amount", $budget); ?>
  <?php echo form_csrf_token(); ?>
  <div type="submit"><?php echo $submit_label; ?></div>
</form>
