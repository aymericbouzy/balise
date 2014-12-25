<?php

  include "constants.php";
  session_start();

  include GLOBAL_PATH."database.php";
  include GLOBAL_PATH."agregation.php";
  include GLOBAL_PATH."sql.php";
  include GLOBAL_PATH."urlrewriting.php";
  include GLOBAL_PATH."before_actions.php";
  include GLOBAL_PATH."common.php";
  include GLOBAL_PATH."csrf.php";

  include MODEL_PATH."budget.php";
  include MODEL_PATH."tag.php";
  include MODEL_PATH."operation.php";
  include MODEL_PATH."operation_types.php";
  include MODEL_PATH."subsidy.php";
  include MODEL_PATH."request.php";
  include MODEL_PATH."student.php";
  include MODEL_PATH."binet.php";
  include MODEL_PATH."wave.php";
  include MODEL_PATH."term.php";

  include HELPER_PATH."common.php";
  include HELPER_PATH."pretty_print.php";
  include HELPER_PATH."tag.php";
  include HELPER_PATH."sidebar.php";
