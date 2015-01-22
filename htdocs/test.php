<?php
  include "global/initialisation.php";

  $test = send_email(51, "test", "<p>
    qmlsjdfmqlf
  </p>");

  var_dump($test);
