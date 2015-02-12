<form role="form" id="budget" action="/<?php echo path($form_action, "budget", $form_action == "create" ? "" : $budget["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php
    if ($_GET["action"] == "new") {
      echo form_group_radio("sign", array("1" => "Sortie d'argent", "0" => "Rentrée d'argent"), $budget, "budget");
    }
  ?>
  <?php echo form_group_text("Nom :", "label", $budget, "budget"); ?>
  <?php echo form_group_text("Tags (séparés par des ';') :", "tags_string", $budget, "budget"); ?>
  <?php echo form_group_text("Montant prévisionnel :", "amount", $budget, "budget"); ?>
  <?php echo form_group_text("Subventions espérées :", "subsidized_amount", $budget, "budget"); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
<!-- // <script charset="utf-8">
//   function hide_subsidized_amount_if_income() {
//     var subsidized_amount_form_group = document.getElementsById("subsidized_amount_form_group");
//     subsidized_amount_form_group.class
//   }
//
//   function show_subsidized_amount_if_expense() {
//     var subsidized_amount_form_group = document.getElementsById("subsidized_amount_form_group");
//     subsidized_amount_form_group.class
//   }
// </script> -->
