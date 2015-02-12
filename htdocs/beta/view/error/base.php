<div id="error-wrapper">
  <div id="centering">
    <div class="error-container">
      <p id="art"><i class="fa fa-fw <?php echo $error_icon; ?>"></i></p>
      <p id="errorcode"><?php echo $_GET["action"]; ?></p>
      <p id="message"><?php echo $error_message; ?></p>
      <?php // echo link_to($_SERVER["HTTP_REFERER"], "Retourner sur le site", array("class" => "btn btn-primary btn-lg")); ?>
    </div>
  </div>
</div>
