<!DOCTYPE html>
  <html lang="">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="nicolet" >
    <title>Balise Trézo</title>
    <link rel="shortcut icon" href="">
    		<!--Minified Bootstrap CSS-->
    		<link rel="stylesheet" href="<?php echo $ASSET_PATH; ?>dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $ASSET_PATH; ?>dist/css/bootstrap-theme.min.css">
        <!--Switch CSS-->
        <link rel="stylesheet" href="<?php echo $ASSET_PATH; ?>css/bootstrap-switch.css">
        <!-- Custom CSS -->
        <link href="<?php echo $ASSET_PATH; ?>css/user-home.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="<?php echo $ASSET_PATH; ?>font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

			<!-- jQuery -->
        <script src="<?php echo $ASSET_PATH; ?>js/jquery.js"></script>
        <!--Core Bootstrap JS-->
        <script src="<?php echo $ASSET_PATH; ?>dist/js/bootstrap.min.js"></script>
        <!--Switch JS-->
        <script src="<?php echo $ASSET_PATH; ?>js/bootstrap-switch.js"></script>

    <!--[if IE]>
      <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div id="wrapper">
      <?php
        if ($_GET["controller"] == "error" ) {

        } else {
          include $LAYOUT_PATH."structure.php";
        }
      ?>
      <div id="page-wrapper">
        <?php
          include $LAYOUT_PATH."flash.php";
          include $VIEW_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/" : "").$_GET["controller"]."/".$_GET["action"].".php";
        ?>
      </div>
      <footer>
        <?php
          include $LAYOUT_PATH."footer.php";
        ?>
      </footer>
    </div>
  </body>
</html>
