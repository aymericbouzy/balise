<!DOCTYPE html>
<html>
  <head>

  </head>
  <body>
    <header>
      <?php
        include $LAYOUT_PATH."header.php";
      ?>
    </header>
    <aside>
      <?php
        include $LAYOUT_PATH."aside.php";
      ?>
    </aside>
    <div class="flash">
      <?php
        include $LAYOUT_PATH."flash.php";
      ?>
    </div>
    <?php
      include $VIEW_PATH.(isset($_GET["prefix"]) ? $_GET["prefix"]."/" : "").$_GET["controller"]."/".$_GET["action"].".php";
    ?>
  </body>
</html>
