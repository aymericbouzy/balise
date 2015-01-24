<?php
  if (isset($operation["binet_validation_by"])) {
    ?>
    <h1>Modifier l'allocation de l'opération au budget</h1>
    <?php
  } else {
    ?>
    <h1>Ajouter l'opération au budget</h1>
    <?php
  }
?>
<p>
  Montant total de l'opération : <?php echo pretty_amount($operation["amount"]); ?>
</p>
<form role="form" id="operation" action="/<?php echo path("validate", "operation", $operation["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php
  foreach ($binet_budgets as $budget) {
    echo form_group_text(pretty_budget($budget["id"]), adds_amount_prefix($budget), $operation, "operation");
  }
  ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button("Enregistrer"); ?>
  <?php echo link_to(path("delete", "operation", $operation["id"], binet_prefix($binet, $term)), "Supprimer l'opération", "btn btn-danger"); ?>
</form>
