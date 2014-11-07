<div id="header-left">
  <?php echo link_to(path("", "binet", $binet["id"]."/".$term), $binet["name"]."<span class=\"binet-term\">".$term."</span>") ?>
  <ul>
    <?php foreach(binet_admin() as $binet_admin) {
      $binet_admin["name"] = select_binet($binet_admin["binet"], array("name"))["name"];
      ?>
      <li>
        <?php echo link_to(path("", "binet", $binet_admin["binet"]."/".$binet_admin["term"]), $binet_admin["name"]."<span class=\"binet-term\">".$binet_admin["term"]."</span>") ?>
      </li>
      <?php
    }
    ?>
  </ul>
</div>
<div id="header-center">

</div>
<div id="header-right">

</div>
