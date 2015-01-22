<?php
  include "global/initialisation.php";

  $test = send_email(51, "and with a different subject ?", "<p>
    how about this ?
  </p>");

  var_dump($test);
