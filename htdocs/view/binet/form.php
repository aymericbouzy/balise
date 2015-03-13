<?php
  echo form_input("Nom :", "name", $form);
  echo form_input("Promotion du binet :", "term", $form);
  echo form_input("Description publique du binet :", "description", $form);
  echo form_input("Description des démarches à effectuer pour récupérer les subventions :", "subsidy_steps", $form);
  echo form_submit_button($_GET["action"] == "new" ? "Créer" : "Enregistrer"); ?>
