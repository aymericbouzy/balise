<ul>
  <li>
    <?php
      $number_pending_validations = count_pending_validations($binet["id"], $term);
      echo link_to(
        path("validations", "binet", $binet["id"]."/".$term),
        "Validations".($number_pending_validations > 0 ? " <span class=\"counter\">".$number_pending_validations."</span>" : "")
      );
    ?>
  </li>
  <li><?php echo link_to(path("", "budget", "", "binet/".$binet["id"]."/".$term), "Comptes"); ?></li>
  <li><?php echo link_to(path("", "request", "", "binet/".$binet["id"]."/".$term), "Subventions"); ?></li>
  <?php if (select_binet($binet["id"], array("subsidy_provider"))["subsidy_provider"] == 1) {
    ?>
    <li class = "seperator"></li>
    <li>
      <?php echo link_to(path("", "wave", "", "binet/".$binet["id"]."/".$term), "Vagues de subvention"); ?>
    </li>
    <?php
  }
  if ($binet["id"] == $KES_ID) {
    ?>
    <li class = "seperator"></li>
    <li>
      <?php echo link_to(path("admin", "binet"), "Administration"); ?>
    </li>
    <?php
  }
  ?>
</ul>
