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
     	<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/common.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/backgrounds.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/flashcards.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/piechart.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/buttons.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/modals.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/forms.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/panels.css" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/features/table.css" type="text/css">
      <!--- Datetimepicker -->
      <link href="<?php echo ASSET_PATH; ?>css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
      <!-- Selectsearch -->
      <link rel="stylesheet" type="text/css" href="<?php echo ASSET_PATH; ?>silviomoreto-bootstrap-select-83d5a1b/dist/css/bootstrap-select.css">
      <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
      <script type="text/javascript" src="<?php echo ASSET_PATH; ?>silviomoreto-bootstrap-select-83d5a1b/dist/js/bootstrap-select.js"></script>

     	<!-- Custom Fonts -->
     	<link href="<?php echo ASSET_PATH; ?>font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>fontastic/styles.css" type="text/css">

  		<!-- JavaScript -->
      <script src="<?php echo ASSET_PATH; ?>js/common.js"></script>

      </script>
  		<!-- jQuery -->
     	<script src="<?php echo ASSET_PATH; ?>js/jquery.js"></script>
     	<!--Core Bootstrap JS-->
     	<script src="<?php echo ASSET_PATH; ?>dist/js/bootstrap.js"></script>
      <script src="<?php echo ASSET_PATH; ?>js/moment.js"></script>
      <script src="<?php echo ASSET_PATH; ?>js/bootstrap-datetimepicker.min.js"></script>
      <script src = "<?php echo ASSET_PATH; ?>js/jquery.textfill.min.js"></script>
   	  <!--[if IE]>
      	<script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    	<![endif]-->

      <?php
        $css_file_for_action = ASSET_PATH."css/action/".$_GET["action"].".css";
        if (file_does_exist($css_file_for_action)) {
		      ?>
		      <link rel="stylesheet" href="<?php echo $css_file_for_action; ?>">
		      <?php
        }
      ?>

      <?php
	      $css_file_for_controller = ASSET_PATH."css/controller/".$_GET["controller"].".css";
	      if (file_does_exist($css_file_for_controller)) {
	        ?>
	        <link rel="stylesheet" href="<?php echo $css_file_for_controller; ?>">
	        <?php
	      }
      ?>

      <?php echo initialize_tooltips(); ?>

      <link rel="icon" href="<?php echo IMG_PATH; ?>favicon.ico">
      <link rel="apple-touch-icon" href="<?php echo IMG_PATH; ?>balise.png">

  </head>
  <body>
    <div id="wrapper">
      <?php generate_csrf_token(); ?>
      <?php
        if (!($_GET["controller"] == "error" || $_GET["controller"] == "home" && in_array($_GET["action"], array("welcome", "chose_identity")))) {
          include LAYOUT_PATH."structure.php";
        }
      ?>
      <div id="page-wrapper">
        <?php
          include LAYOUT_PATH."flash.php";
          include VIEW_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/" : "").$_GET["controller"]."/".$_GET["action"].".php";
        ?>
      </div>
    </div>

    <footer>
      <?php
      include LAYOUT_PATH."footer.php";
      ?>
    </footer>
    <?php echo modal("bug-report", "Contacter l'équipe du site Balise", get_html_form("bug_report"));?>
    <script src = "<?php echo ASSET_PATH; ?>js/common.js"></script>
  </body>
</html>
