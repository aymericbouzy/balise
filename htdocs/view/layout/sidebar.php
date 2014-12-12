<!-- <ul>
  <li>
    <?php
      $number_pending_validations = count_pending_validations($binet, $term);
      echo link_to(
        path("validations", "binet", binet_term_id($binet, $term)),
        "Validations".($number_pending_validations > 0 ? " <span class=\"counter\">".$number_pending_validations."</span>" : "")
      );
    ?>
  </li>
  <li><?php echo link_to(path("", "budget", "", binet_prefix($binet, $term)), "Comptes"); ?></li>
  <li><?php echo link_to(path("", "request", "", binet_prefix($binet, $term)), "Subventions"); ?></li>
  <?php if (select_binet($binet, array("subsidy_provider"))["subsidy_provider"] == 1) {
    ?>
    <li class = "seperator"></li>
    <li>
      <?php echo link_to(path("", "wave", "", binet_prefix($binet, $term)), "Vagues de subvention"); ?>
    </li>
    <?php
  }
  if ($binet == KES_ID) {
    ?>
    <li class = "seperator"></li>
    <li>
      <?php echo link_to(path("admin", "binet"), "Administration"); ?>
    </li>
    <?php
  }
  ?>
</ul> -->

<div class="collapse navbar-collapse navbar-ex1-collapse">
  <ul class="nav navbar-nav side-nav">
    <?php
      echo li_link(
        link_to(path("", "budget", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-home\"></i> Accueil"),
        $_GET["controller"] == "budget" || $_GET["controller"] == "operation"
      );
      $number_pending_validations = count_pending_validations($binet, $term);
      echo li_link(
        link_to(
          path("validations", "binet", binet_term_id($binet, $term)),
          "<i class=\"fa fa-fw fa-check\"></i> Validations".($number_pending_validations > 0 ? " <span class=\"counter\">".$number_pending_validations."</span>" : "")
        ),
        $_GET["controller"] == "binet" && $_GET["action"] == "validation"
      );
      echo li_link(
        link_to(path("", "request", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-money\"></i> Subventions"),
        $_GET["controller"] == "request"
      );
      if (select_binet($binet, array("subsidy_provider"))["subsidy_provider"] == 1) {
        ?>
        <li class="divider"></li>
        <?php
        echo li_link(
          link_to(path("", "wave", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-star\"></i> Vague de subventions"),
          $_GET["controller"] == "wave"
        );
      }
      if ($binet == KES_ID) {
        ?>
        <li class="divider"></li>
        <?php
        echo li_link(
          link_to(path("admin", "binet"), "<i class=\"fa fa-fw fa-desktop\"></i> Administration"),
          $_GET["controller"] == "admin"
        );
      }
    ?>
  </ul>
</div>
