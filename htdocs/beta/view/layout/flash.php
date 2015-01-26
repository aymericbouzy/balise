<?php

  function print_flash($class) {
    if (!empty($_SESSION[$class])) {
      foreach ($_SESSION[$class] as $flash) {
        ?>
          <div class="flashcard <?php echo $class;?> alert alert-dismissible fade in">
            <button class="close" data-dismiss="alert">
              <i class="fa fa-fw fa-close"></i>
            </button>
            <?php echo $flash; ?>
          </div>
        <?php
      }
      unset($_SESSION[$class]);
    }
  }
?>

  <div class="flash-container">
    <?php
      print_flash("notice");
      print_flash("error");
    ?>
  </div>
