<div class="collapse navbar-collapse navbar-ex1-collapse">
  <ul class="nav navbar-nav side-nav">
    <!-- Choose binet using dropdown menu -->
    <li>
      <!-- For all binets -->
    	<a href="javascript:;" data-target="#binets" data-toggle="collapse"><?php echo pretty_binet(binet, false); ?> </a>
				<ul id="binets" class="collapse">
					<?php
            foreach (select_terms(array("student" => connected_student())) as $term) {
  						$term = select_term_binet($term["id"], array("id","binet","term"))
  						?>
  						<li>
  							<?php
                $link = in_array($_GET["controller"], array("budget", "operation", "request")) ?
                  path("", $_GET["controller"], "", binet_prefix($term["binet"], $term["term"])) :
                  path("", "binet", binet_term_id($term["binet"], $term["term"]));
                echo link_to($link, pretty_binet_term($term["id"], false));
                ?>
  						</li>
  						<?php
						}
					?>
				</ul>
    </li>
    <!-- Accueil : links to budget/operations page -->
    <?php
      if (has_viewing_rights(binet, term)) {
        echo li_link(
          link_to(path("", "budget", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-bar-chart\"></i> Budget"),
          $_GET["controller"] == "budget"
        );
        $number_pending_validations = count_pending_validations(binet, term);
        echo li_link(
          link_to(
            path("", "operation", "", binet_prefix(binet, term)),
            "<i class=\"fa fa-fw fa-database\"></i> Opérations".(($number_pending_validations > 0 && has_editing_rights(binet,term))? " <span class=\"badge\">".$number_pending_validations."</span>" : "")
          ),
          $_GET["controller"] == "operation"
        );
      }
      echo li_link(
        link_to(path("", "request", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-money\"></i> Subventions"),
        $_GET["controller"] == "request"
      );
      // If subsidy provider
      $sidebar_waves = array_merge(select_waves(array("binet" => binet, "term" => term)), select_waves(array("binet" => binet, "term" => term, "state" => "rough_draft")));
      if (!is_empty($sidebar_waves)) {
        ?>
        <li class="divider"></li>
        <?php
        echo li_link(
        link_to(path("", "wave", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-star\"></i> Vague de subventions"),
          $_GET["controller"] == "wave"
        );
      }
      if (has_viewing_rights(binet, term)) {
        echo li_link(
          link_to(path("", "member", "", binet_prefix(binet, term)), "<i class=\"fa fa-fw fa-group\"></i> Membres"),
          $_GET["controller"] == "member"
        );
      }
    ?>
  </ul>
</div>
