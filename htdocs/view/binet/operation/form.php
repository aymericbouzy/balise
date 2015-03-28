<?php echo form_input("Description :", "comment", $form); ?>
<div class="row">
  <div class="col-md-5">
    <?php echo form_input("Référence de facture :", "bill", $form); ?>
  </div>
  <div class="col-md-4">
    <?php echo form_input("Date de la facture :", "bill_date", $form); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-5">
    <?php echo form_input("Référence de paiement :", "payment_ref", $form); ?>
  </div>
  <div class="col-md-4">
    <?php echo form_input("Date du paiement :", "payment_date", $form); ?>
  </div>
  <div class="col-md-3">
    <?php echo form_input("", "type", $form, array("options" => option_array(select_operation_types(), "id", "name", "operation_type", array("icon" => "icon")), "search" => false)); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-9">
    <?php echo form_input("Montant :", "amount", $form); ?>
  </div>
  <div class="col-md-3">
    <?php echo form_input(array("0" => "Dépense", "1" => "Recette"), "sign", $form, array("disabled" => $_GET["action"] == "edit" ? 1 : 0, "selection_method" => "radio")); ?>
  </div>
</div>
<?php echo form_input("Payé par :", "paid_by", $form, array("options" => array_true_merge(option_array(select_students(), "id", "name", "student"), paid_by_static_options()))); ?>
<?php
  if (is_empty($_GET["prefix"])) {
    echo form_input("", "binet", $form, array("hidden" => 1));
    echo form_input("Appartient à la promotion suivante", "next_term", $form);
  }
?>
<?php echo form_submit_button($_GET["action"] == "new" ? "Sauvegarder" : "Enregistrer"); ?>
