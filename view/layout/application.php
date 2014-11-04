<!DOCTYPE html>
<html>
  <head>

  </head>
  <body>
    <?php
      switch ($_GET["controller"]) {
      case "frankiz":
        break;
      default:
        include $LAYOUT_PATH."structure.php";
      }
      include $VIEW_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/" : "").$_GET["controller"]."/".$_GET["action"].".php";
    ?>
  </body>
</html>
