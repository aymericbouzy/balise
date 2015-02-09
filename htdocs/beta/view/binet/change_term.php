<div class="form-container">
  <h1>Mettre à jour le mandat actuel du binet</h1>
  <form role="form" id="binet" action="/<?php echo path("set_term", "binet", $binet["id"]); ?>" method="post">
    <?php echo form_group_text("Année de promotion du nouveau mandat :", "term", $binet, "binet"); ?>
    <?php echo form_csrf_token(); ?>
    <?php echo form_submit_button("Mettre à jour"); ?>
  </form>
</div>
