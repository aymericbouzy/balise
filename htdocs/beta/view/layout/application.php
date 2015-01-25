<!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="nicolet" >
    <title>Balise Trézo</title>

    	<!-- CSS -->
    	<!--Minified Bootstrap CSS-->
    	<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>dist/css/bootstrap.min.css" type="text/css">
     	<!-- Custom CSS -->
     	<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/user-home.css" type="text/css">

     	<!-- Custom Fonts -->
     	<link href="<?php echo ASSET_PATH; ?>font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- JavaScript -->
		<!-- jQuery -->
     	<script src="<?php echo ASSET_PATH; ?>js/jquery.js"></script>
     	<!--Core Bootstrap JS-->
     	<script src="<?php echo ASSET_PATH; ?>dist/js/bootstrap.min.js"></script>

   	<!--[if IE]>
      	<script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    	<![endif]-->

      <?php
        $css_file_for_action = "beta/asset/css/action/".$_GET["action"].".css";
        if (file_exists($css_file_for_action)) {
		      ?>
		      <link rel="stylesheet" href="/<?php echo $css_file_for_action; ?>">
		      <?php
        }
      ?>

      <?php
	      $css_file_for_controller = "beta/asset/css/controller/".$_GET["controller"].".css";
	      if (file_exists($css_file_for_controller)) {
	        ?>
	        <link rel="stylesheet" href="/<?php echo $css_file_for_controller; ?>">
	        <?php
	      }
      ?>

  </head>
  <body>
    <div id="wrapper">
      <?php
        if ($_GET["controller"] == "error" || $_GET["controller"] == "home" && $_GET["action"] == "welcome"){

        } else {
          include LAYOUT_PATH."structure.php";
        }
      ?>
      <div id="page-wrapper">
        <?php
          include LAYOUT_PATH."flash.php";
          include VIEW_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/" : "").$_GET["controller"]."/".$_GET["action"].".php";
        ?>
      </div>
      <footer>
        <?php
          include LAYOUT_PATH."footer.php";
        ?>
      </footer>
    </div>

    <script src = "<?php echo ASSET_PATH; ?>js/common.js"></script>
  </body>
</html>
