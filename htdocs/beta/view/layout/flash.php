<?php

  function print_flash($class) {
    if (!empty($_SESSION[$class])) {
      foreach ($_SESSION[$class] as $flash) {
        ?>

        <?php echo $flash; ?>

        <?php
      }
      unset($_SESSION[$class]);
    }
  }

  print_flash("notice");
  print_flash("error");
