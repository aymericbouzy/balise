<div class="collapse navbar-collapse navbar-ex1-collapse">
  <ul class="nav navbar-nav side-nav">
    <!-- Show current binet -->
    <li>
      <?php echo link_to(path("show","binet",$binet),
      		"<i class=\"fa fa-fw fa-eye\"></i> ".pretty_binet_term(make_term_id($binet, $term),false));?>
    </li>
    <!--  Change current term  -->
    <?php if(sizeof(select_terms(array("binet" => $binet))) > 1){?>
	    <li id="choose_promo_collapsed_list">
	    		<a href="javascript:;" data-target="#terms" data-toggle="collapse">
	    			Voir une autre promotion <i class="fa fa-fw fa-chevron-down"></i>
	    		</a>
	    		<ul class="collapse" id="terms">
	    			<?php echo pretty_terms_list($binet,true); ?>
	    		</ul>
	    </li>
	  <?php } ?>
    <!-- Budget and operation -->
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
      // Subventions
      echo li_link(
        link_to(path("", "request", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-money\"></i> Subventions"),
        $_GET["controller"] == "request"
      );
      // If subsidy provider : Vagues de subventions
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
