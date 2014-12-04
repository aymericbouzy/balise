<!DOCTYPE html>
  <html lang="">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Balise Trézo</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="<?php echo $ASSET_PATH; ?>dist/css/bootstrap.min.css">

    <?php if ($_GET["controller"] == "home" && $_GET["action"] == "welcome") {
      ?>
        <link rel="stylesheet" href="<?php echo $ASSET_PATH; ?>dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $ASSET_PATH; ?>css/home.css">
      <?php
    } else {
      ?>
        <link rel="stylesheet" href="<?php echo $ASSET_PATH; ?>dist/css/bootstrap.css">
        <!--Switch CSS-->
        <link rel="stylesheet" href="<?php echo $ASSET_PATH; ?>css/bootstrap-switch.css">
        <!-- Custom CSS -->
        <link href="<?php echo $ASSET_PATH; ?>css/user-home.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="<?php echo $ASSET_PATH; ?>font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!--Switch JS-->
        <script src="<?php echo $ASSET_PATH; ?>js/bootstrap-switch.js"></script>
        <!--Core Bootstrap JS-->
        <script src="<?php echo $ASSET_PATH; ?>dist/js/bootstrap.min.js"></script>
        <!-- jQuery -->
        <script src="<?php echo $ASSET_PATH; ?>js/jquery.js"></script>
      <?php
    } ?>

    <!--[if IE]>
      <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div id="wrap">
      <?php
        if ($_GET["controller"] == "error" || ($_GET["controller"] == "home" && $_GET["action"] == "welcome")) {

        } else {
          include $LAYOUT_PATH."structure.php";
        }
      ?>
      <div id="page_wraper">
        <?php
          include $LAYOUT_PATH."flash.php";
          include $VIEW_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/" : "").$_GET["controller"]."/".$_GET["action"].".php";
        ?>
      </div>
      <?php if ($_GET["controller"] == "home" && $_GET["action"] == "welcome") {
        ?>
          <div id="footer">
          <div class="container">
              <p class="text-muted">Page créée avec <a href="http://www.bootstrap.com">bootstrap</a></p>
          </div>
          </div>

          <ul class="nav pull-right scroll-top">
              <li><a href="#" title="Scroll to top"><i class="glyphicon glyphicon-chevron-up"></i></a></li>
          </ul>
          <!-- Page script -->
          <script src="assets/js/login_page.js"></script>
        <?php
      } ?>
      <footer>
        <?php
          include $LAYOUT_PATH."footer.php";
        ?>
      </footer>
    </div>

    <!-- Bootstrap Core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  </body>
</html>
