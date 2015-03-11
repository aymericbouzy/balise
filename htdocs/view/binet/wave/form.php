<?php

  echo form_input("Les binets peuvent soumettre leur demande de subvention jusqu'au :", "submission_date", $form);
  echo form_input("Les subventions seront valables jusqu'au :", "expiry_date", $form);
  echo form_input("Critères d'obtention des subventions :", "description", $form);
  echo form_input("Question à poser aux binets :", "question", $form);
  echo form_input("Explication pour les subventions accordées : ".
    info_tooltip("Ce texte d'explication globale sur les subventions attribuées est visible par tout le monde.
    Tu peux indiquer la date de l'IK correspondant pour des explications plus détaillées."), "explanation", $form);
  echo form_input("Montant total de subventions à répartir :", "amount", $form);
  echo form_submit_button($_GET["action"] == "edit" ? "Mettre à jour" : "Créer");
