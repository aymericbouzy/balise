<?php
  include "global/initialisation.php";

  $binet_rock = select_binets(array("name" => "Rock"))[0]["id"];
  $student = connected_student();
  add_admin_binet($student, $binet_rock, 2012);
