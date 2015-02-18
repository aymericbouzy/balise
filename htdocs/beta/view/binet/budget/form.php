<?php echo form_input(array("1" => "Dépense", "0" => "Recette"), "sign", $form, array("disabled" => $_GET["action"] == "edit" ? 1 : 0, "selection_method" => "radio")); ?>
<?php echo form_input("Nom :", "label", $form); ?>
<?php echo form_input("Tags (séparés par des ';') :", "tags", $form); ?>
<?php echo form_input("Montant prévisionnel :", "amount", $form); ?>
<?php echo form_submit_button($_GET["action"] == "new" ? "Sauvegarder" : "Enregistrer"); ?>
