<form role="form" id="budget" action="<?php echo path($form_action, "budget", "", "binet/".$binet["id"]."/".$term); ?>" method="post">
  <input type="hidden" name="sign" value="<?php echo $form_budget_sign ?>">
  <?php echo form_group_text("Nom :", "label", $budget); ?>
  <?php echo form_group_text("Tags (séparés par des ';') :", "tags_string", $budget); ?>
  <?php echo form_group_text("Montant prévisionnel :", "amount", $budget); ?>
  <div type="submit">Créer</div>
</form>
