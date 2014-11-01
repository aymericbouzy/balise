<!DOCTYPE html>
<html>
  <head>

  </head>
  <body>
    <?php
      include $VIEW_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/" : "").$_GET["controller"]."/".$_GET["action"].".php";
    ?>
  </body>
</html>
