<h1>Nouveau binet</h1>
<form role="form" id="binet" action="<?php echo path("create", "binet"); ?>" method="post">
  <?php echo form_group_text("Nom :", "name", $binet); ?>
  <?php echo form_group_text("Promotion du mandat courant :", "term", $binet); ?>
  <?php echo form_csrf_token(); ?>
  <div type="submit">Créer</div>
</form>
