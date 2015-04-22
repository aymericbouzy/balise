<?php echo form_input("Administrateur :", "student", $form, array("options" => option_array($students, "id", "name", "student"))); ?>
<?php echo form_input("Créer l'administrateur pour la promo suivante", "next_term", $form); ?>
<?php echo form_submit_button("Créer"); ?>
