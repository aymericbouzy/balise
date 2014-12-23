<h1>Nouvel administrateur du binet <?php echo pretty_binet($binet); ?></h1>
<form role="form" id="admin" action="<?php echo path("create", "admin", "", binet_prefix($binet, $term)); ?>" method="post">
  <?php echo form_group_text("Administrateur :", "student", $admin); ?>
  <?php echo form_group_text("Mandat :", "term", $admin); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button("Créer"); ?>
</form>
