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
