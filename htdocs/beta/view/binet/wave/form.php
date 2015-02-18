<?php echo form_input("Les binets peuvent soumettre leur demande de subvention jusqu'au :", "submission_date", $form); ?>
<?php echo form_input("Les subventions seront valables jusqu'au :", "expiry_date", $form); ?>
<?php echo form_input("Question à poser aux binets :", "question", $form); ?>
<?php echo form_submit_button($_GET["action"] == "edit" ? "Mettre à jour" : "Créer"); ?>
