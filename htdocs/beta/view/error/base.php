<div id="error-wrapper">
  <div id="centering">
    <div class="error-container">
      <p id="art"><i class="fa fa-fw <?php echo $error_icon; ?>"></i></p>
      <p id="errorcode"><?php echo $_GET["action"]; ?></p>
      <p id="message"><?php echo $error_message; ?></p>
      <?php
        $redirect_path = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : path("welcome", "home");
        echo link_to($redirect_path, "Retourner sur le site", array("class" => "btn btn-primary btn-lg"));
      ?>
    </div>
  </div>
</div>
