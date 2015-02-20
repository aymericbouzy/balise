<?php echo form_input("Nom :", "label", $form); ?>
<?php echo form_input("Tags :", "tags", $form, array("options" => option_array(select_tags(), "id", "name", "tag"))); ?>
<div class="row">
  <div class="col-md-9">
    <?php echo form_input("Montant prévisionnel :", "amount", $form); ?>
  </div>
  <div class="col-md-3">
    <?php echo form_input(array("0" => "Dépense", "1" => "Recette"), "sign", $form, array("disabled" => $_GET["action"] == "edit" ? 1 : 0, "selection_method" => "radio")); ?>
  </div>
</div>
<?php echo form_submit_button($_GET["action"] == "new" ? "Sauvegarder" : "Enregistrer"); ?>
