<?php

  echo form_input("Ton message :", "report", $form);
  echo form_input("", "information", $form, array("hidden" => STATE != "development"));
  echo form_submit_button("Envoyer");
