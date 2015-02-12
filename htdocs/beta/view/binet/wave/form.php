<form role="form" id="wave" action="/<?php echo path($form_action, "wave", $form_action == "create" ? "" : $wave["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php echo form_group_date("Les binets peuvent soumettre leur demande de subvention jusqu'au :", "submission_date", $wave, "wave"); ?>
  <?php echo form_group_date("Les subventions seront valables jusqu'au :", "expiry_date", $wave, "wave"); ?>
  <?php echo form_group_textarea("Question à poser aux binets :", "question", $wave, "wave"); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
