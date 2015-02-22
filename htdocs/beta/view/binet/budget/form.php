<?php echo form_input("Nom :", "label", $form); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-10">
      <?php echo form_input("Tags :", "tags", $form, array("options" => option_array(select_tags(), "id", "name", "tag"))); ?>
    </div>
    <div class="col-md-2" style="padding-top:24px;">
      <?php echo form_submit_button(new_tag_submit_value); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-9">
      <?php echo form_input("Montant prévisionnel :", "amount", $form); ?>
    </div>
    <div class="col-md-3">
      <?php echo form_input(array("0" => "Dépense", "1" => "Recette"), "sign", $form, array("disabled" => $_GET["action"] == "edit" ? 1 : 0, "selection_method" => "radio")); ?>
    </div>
  </div>
  <div id="expected_subsidies">
    <?php echo form_input("Subventions espérées :", "subsidized_amount", $form, array("html_decoration" => array("placeholder" => "0.00 - Les subventions que vous espérez recevoir pour ce budget."))); ?>
  </div>
  <?php echo form_submit_button($_GET["action"] == "new" ? "Sauvegarder" : "Enregistrer"); ?>
</div>

<script charset="utf-8">
  var id="expected_subsidies";
  $('input[id="sign1"]').change(function(){
    if($(this).is(':checked'))
    {
      show_hidden_form_element(id);
    }
  });
  $('input[id="sign0"]').change(function(){
    if($(this).is(':checked'))
    {
      hide_form_element(id);
    }
  });
</script>
