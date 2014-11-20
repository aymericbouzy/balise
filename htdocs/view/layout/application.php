<!DOCTYPE html>
  <html lang="">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Balise Trézo</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="../dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/bootstrap.css">
    <!--Switch CSS-->
    <link rel="stylesheet" href="../css/bootstrap-switch.css">
    <!-- Custom CSS -->
    <link href="../css/user-home.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!--Switch JS-->
    <script src="../assets/js/bootstrap-switch.js"></script>
    <!--Core Bootstrap JS-->
    <script src="../dist/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="../assets/js/jquery.js"></script>
    <!--[if IE]>
      <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php
      switch ($_GET["controller"]) {
      case "frankiz":
        break;
      default:
        include $LAYOUT_PATH."structure.php";
      }
    ?>
    <div id="main">
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
  </body>
</html>
