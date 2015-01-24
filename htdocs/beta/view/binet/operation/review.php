<?php
  if (isset($operation["binet_validation_by"])) {
    ?>
    <?php
  } else {
    ?>
    <h1>Ajouter l'opération au budget</h1>
    <form role="form" id="operation" action="/<?php echo path("validate", "operation", $operation["id"], binet_prefix($binet, $term)); ?>" method="post">
      <?php
      foreach ($budgets as $budget) {
        echo form_group_text(pretty_budget($budget), adds_amount_prefix($budget), $operation, "operation");
      }
      ?>
      <?php echo form_csrf_token(); ?>
      <?php echo form_submit_button("Enregistrer"); ?>
    </form>

    <?php
  }
