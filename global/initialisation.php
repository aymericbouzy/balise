<?php

  include "constants.php";
  session_start();
  include $GLOBAL_PATH."database.php";
  include $GLOBAL_PATH."agregation.php";
  include $GLOBAL_PATH."sql.php";
  include $GLOBAL_PATH."urlrewriting.php";
  include $GLOBAL_PATH."before_actions.php";
  include $GLOBAL_PATH."common.php";

  include $MODEL_PATH."budget.php";
  include $MODEL_PATH."tag.php";
  include $MODEL_PATH."operation.php";
  include $MODEL_PATH."subsidy.php"; // depends on budget.php
  include $MODEL_PATH."request.php"; // depends on subsidy.php
  include $MODEL_PATH."binet.php"; // depends on budget.php
  include $MODEL_PATH."wave.php";