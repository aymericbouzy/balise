<?php

  function print_flash($class) {
    if (!empty($_SESSION[$class])) {
      $cssClass= "alert-default";
      if($class== "notice"){
        $cssClass= "alert-success";
      }else if($class== "error"){
        $cssClass= "alert-danger":
      }
      foreach ($_SESSION[$class] as $flash) {
        ?>
        <div class="col-lg-4">
          <div class="alert <?php echo $cssClass ?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert">
              <span aria-hidden="true">×</span><span class="sr-only">Fermer</span></button>
              <strong>
                <?php echo $flash; ?>
              </strong>
          </div>
        </div>
        <?php
      }
      unset($_SESSION[$class]);
    }
  }
?>

  <div class="row">
    <?php
      print_flash("notice");
      print_flash("error");
    ?>
  </div>
