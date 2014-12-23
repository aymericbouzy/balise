<form role="form" id="wave" action="<?php echo path($form_action, "wave", $form_action == "create" ? "" : $wave["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php echo form_group_text("Les binets peuvent soumettre leur demande de subvention jusqu'au :", "submission_date", $wave); ?>
  <?php echo form_group_text("Les subventions seront valables jusqu'au :", "expiry_date", $wave); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
