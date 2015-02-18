<form role="form" id="budget" action="/<?php echo path($form_action, "budget", $form_action == "create" ? "" : $budget["id"], binet_prefix($binet, $term)); ?>" method="post">
  <?php
    if ($_GET["action"] == "new") {
      echo form_group_radio("sign", array("1" => "Sortie d'argent", "0" => "Rentrée d'argent"), $budget, "budget");
    }
  ?>
  <?php echo form_group_text("Nom :", "label", $budget, "budget"); ?>
  <?php echo form_group_text("Mots clés (séparés par des ';') :", "tags_string", $budget, "budget",array("placeholder"=>"Les mots clés qui vous permettront de retrouver le budget.")); ?>
  <?php echo form_group_text("Montant prévisionnel :", "amount", $budget, "budget",array("placeholder"=>"0.00")); ?>
  <div id="expected_subsidies">
    <?php echo form_group_text("Subventions espérées :", "subsidized_amount", $budget, "budget",array("placeholder"=>"0.00 - Les subventions que vous espérez recevoir pour ce budget.")); ?>
  </div>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>

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
