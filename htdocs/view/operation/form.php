<form role="form" id="operation" action="/<?php echo path($form_action, "operation", $form_action == "create" ? "" : $operation["id"]); ?>" method="post">
  <?php echo form_group_select("binet", option_array(select_binets(), "id", "name", "binet"), $operation, "operation"); ?>
  <?php echo form_group_text("term", $operation, "operation"); ?>
  <?php echo form_group_text("comment", $operation, "operation"); ?>
  <?php echo form_group_text("bill", $operation, "operation"); ?>
  <?php echo form_group_text("reference", $operation, "operation"); ?>
  <?php echo form_group_text("amount", $operation, "operation"); ?>
  <?php echo form_group_checkbox("sign", $operation, "operation"); ?>
  <?php echo form_group_select("type", option_array(select_operation_types(), "id", "id", "operation_type"), $operation, "operation"); ?>
  <?php echo form_group_select("paid_by", option_array(select_students(), "id", "name", "student"), $operation, "operation"); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
