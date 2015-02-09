<div class="form-container">
  <h1>Nouveau binet</h1>
  <form role="form" id="binet" action="/<?php echo path("create", "binet"); ?>" method="post">
    <?php echo form_group_text("Nom :", "name", $binet, "binet"); ?>
    <?php echo form_group_text("Promotion du mandat courant :", "term", $binet, "binet"); ?>
    <?php echo form_csrf_token(); ?>
    <?php echo form_submit_button("Créer"); ?>
  </form>
</div>
