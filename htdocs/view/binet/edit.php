<h1>Modifier les informations du binet</h1>
<form role="form" id="binet" action="/<?php echo path("update", "binet", $binet["id"]); ?>" method="post">
  <?php echo form_group_text("Nom :", "name", $binet, "binet"); ?>
  <?php echo form_group_text("Description :", "description", $binet, "binet"); ?>
  <?php
    if (select_binet($binet["id"], array("subsidy_provider"))["subsidy_provider"] == 1) {
      echo form_group_text("Comment récupérer les subventions :", "subsidy_steps", $binet, "binet");
    }
  ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button("Enregistrer"); ?>
</form>
