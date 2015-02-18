<?php
  if (is_empty($_GET["prefix"])) {
    echo form_input("Binet :", "binet", $form, array("options" => option_array(select_binets(), "id", "name", "binet")));
    echo form_input("Appartient à la promotion suivante", "next_term", $form);
  }
?>
<?php echo form_input("Description :", "comment", $form); ?>
<?php echo form_input("Référence de facture :", "bill", $form); ?>
<?php echo form_input("Référence de paiement :", "reference", $form); ?>
<?php echo form_input("Montant :", "amount", $form); ?>
<?php echo form_input(array("1" => "Dépense", "0" => "Recette"), "sign", $form, array("disabled" => $_GET["action"] == "edit" ? 1 : 0, "selection_method" => "radio")); ?>
<?php echo form_input("Type de transaction :", "type", $form, array("options" => option_array(select_operation_types(), "id", "name", "operation_type"))); ?>
<?php echo form_input("Payé par :", "paid_by", $form, array("options" => array_true_merge(option_array(select_students(), "id", "name", "student"), paid_by_static_options()))); ?>
<?php echo form_submit_button($_GET["action"] == "new" ? "Sauvegarder" : "Enregistrer"); ?>
