<?php
  include "config.php";
  session_start();
  include "database.php";

  include $GLOBAL_PATH."agregation.php";
  include $GLOBAL_PATH."sql.php";
  include $GLOBAL_PATH."urlrewriting.php";

  include $MODEL_PATH."binet.php";
  include $MODEL_PATH."spending.php";
  include $MODEL_PATH."tag.php";
  include $MODEL_PATH."income.php";
  include $MODEL_PATH."wave.php";
  include $MODEL_PATH."budget.php";
  include $MODEL_PATH."subsidy.php"; // depends on budget.php
