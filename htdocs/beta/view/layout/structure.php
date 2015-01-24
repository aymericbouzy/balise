<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <?php
    include LAYOUT_PATH."header.php";
    if (isset($_GET["prefix"]) && $_GET["prefix"] == "binet") {
      include LAYOUT_PATH."sidebar.php";
    }
  ?>
</nav>
