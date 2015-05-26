<div class="collapse navbar-collapse navbar-ex1-collapse">
  <ul class="nav navbar-nav side-nav">
    <!-- Show current binet -->
    <li id="binetName_and_switchPromo">
      <span>
        <?php
          echo link_to(path("show", "binet", binet), "<i class=\"fa fa-fw fa-eye\"></i> " . pretty_binet_term(term_id(binet, term), false), array(
            "id" => "show_binet"
          ));
        ?>
        <!-- Change current term -->
        <?php
        if (sizeof(select_terms(array("binet" => binet))) > 1) {
          ?>
          <a href="javascript:;" data-target="#terms" data-toggle="collapse" id="uncollapse_terms">
            <i class="fa fa-fw fa-clock-o"></i><span class="caret"></span>
          </a>
          <?php
        }
        ?>
      </span>
     </li>
    <li>
      <ul class="collapse" id="terms">
        <?php echo pretty_terms_list(binet, true); ?>
      </ul>
    </li>
    <!-- Budget and operation -->
    <?php
    if (has_viewing_rights(binet, term)) {
      echo side_link(link_to(path("", "budget", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-bar-chart\"></i> Budget"), $_GET["controller"] == "budget");
      $number_pending_validations = count_pending_validations(binet, term);
      echo side_link(link_to(path("", "operation", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-database\"></i> Opérations" . (has_editing_rights(binet, term) ? badged_counter($number_pending_validations) : "")), $_GET["controller"] == "operation");
      // Subventions
      $number_rough_draft_requests = count(select_requests(array("state" => "rough_draft", "binet" => binet, "term" => term)));
      echo side_link(link_to(path("", "request", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-money\"></i> Subventions" . (has_editing_rights(binet, term) ? badged_counter($number_rough_draft_requests) : "")), $_GET["controller"] == "request");

      // If subsidy provider : Vagues de subventions
      $sidebar_waves = array_merge(select_waves(array(
        "binet" => binet,
        "term" => term
      )), select_waves(array(
        "binet" => binet,
        "term" => term,
        "state" => "rough_draft"
      )));
      if (!is_empty($sidebar_waves)) {
        ?>
        <li class="divider"></li>
        <?php
        echo side_link(link_to(path("", "wave", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-star\"></i> Vague de subventions" ), $_GET["controller"] == "wave");
      }
    }

    // Membres
    echo side_link(link_to(path("", "member", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-group\"></i> Membres" ), $_GET["controller"] == "member");
    ?>
  </ul>
</div>
