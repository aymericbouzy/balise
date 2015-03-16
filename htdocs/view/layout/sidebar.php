<div class="collapse navbar-collapse navbar-ex1-collapse">
  <ul class="nav navbar-nav side-nav">
    <!-- Choose binet using dropdown menu -->
    <li>
      <?php echo pretty_binet_term(make_term_id($binet, $term));?>
    </li>
    <!-- Accueil : links to budget page -->
    <?php
      if (has_viewing_rights($binet, $term)) {
        echo li_link(
          link_to(path("", "budget", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-bar-chart\"></i> Budget"),
          $_GET["controller"] == "budget"
        );
        $number_pending_validations = count_pending_validations($binet, $term);
        echo li_link(
          link_to(
            path("", "operation", "", binet_prefix($binet, $term)),
            "<i class=\"fa fa-fw fa-database\"></i> Opérations".(($number_pending_validations > 0 && has_editing_rights($binet,$term))? " <span class=\"badge\">".$number_pending_validations."</span>" : "")
          ),
          $_GET["controller"] == "operation"
        );
      }
      echo li_link(
        link_to(path("", "request", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-money\"></i> Subventions"),
        $_GET["controller"] == "request"
      );
      // If subsidy provider
      $sidebar_waves = array_merge(select_waves(array("binet" => $binet, "term" => $term)), select_waves(array("binet" => $binet, "term" => $term, "state" => "rough_draft")));
      if (!is_empty($sidebar_waves)) {
        ?>
        <li class="divider"></li>
        <?php
        echo li_link(
        link_to(path("", "wave", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-star\"></i> Vague de subventions"),
          $_GET["controller"] == "wave"
        );
      }
      // TODO define and create administration link
      if ($binet == KES_ID && false) {
        ?>
        <li class="divider"></li>
        <?php
        echo li_link(
          link_to(path("admin", "binet"), "<i class=\"fa fa-fw fa-desktop\"></i> Administration"),
          $_GET["controller"] == "binet" && $_GET["action"] == "admin"
        );
      }
    ?>
  </ul>
</div>
