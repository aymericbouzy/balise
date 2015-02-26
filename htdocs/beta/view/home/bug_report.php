<?php

  echo form_input("Description du bug et étapes à effectuer pour le reproduire :", "report", $form);
  echo form_input("", "information", $form, array("hidden" => 1));
  echo form_submit_button("Envoyer");
