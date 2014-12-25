<?php
  include "global/initialisation.php";

  var_dump(select_operation_types());
  var_dump(select_operation_type(4, array("id")));
