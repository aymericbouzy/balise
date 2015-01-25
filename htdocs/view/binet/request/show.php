<div class="show-container">
  <!-- TODO : orange en attente, green acceptée, red refusée -->
  <div class="sh-plus red-background opanel">
    <!-- TODO : fa-question requête en attente
                fa-check requête acceptée
                fa-times requête refusée
                -->
    <i class="fa fa-fw fa-question"></i>
    <div class="text"> <!-- TODO Statut de la requête --> </div>
  </div>
  <div class="sh-actions">
		<!-- Les boutons suivants dépendent des autorisations de l'utilisateur 
		Bouton supprimer ?-->
		<div class="round-button red-background opanel">
			<i class="fa fa-fw fa-trash anim"></i>
			<span>Supprimer</span>
		</div>
		<div class="round-button grey-background opanel">
			<i class="fa fa-fw fa-edit anim"></i>
			<span>Modifier</span>
		</div>
		<div class="round-button grey-background opanel">
			<i class="fa fa-fw fa-bookmark-o anim"></i>
			<span>Etudier</span>
		</div>
	</div>
  <div class="sh-title opanel">
    <div class="logo">
      <i class="fa fa-5x fa-money"></i>
    </div>
    <div class="text">
      <p class="main">
        <?php echo pretty_binet_term($request["binet"]."/".$request["term"]); ?>
      </p>
      <p class="sub">
        <?php echo pretty_wave($request["wave"], false); ?>
      </p>
    </div>
  </div>
  <?php
    foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "budget", "requested_amount", "purpose"));
      $budget = select_budget($subsidy["budget"], array("id", "label", "binet", "term"));
      echo link_to(
        path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),
        "<div class=\"sh-req-budget opanel\">
          <div class=\"header\">
            <span class=\"name\">".$budget["label"]."</span>
          </div>
          <div class=\"content\">
            <p class=\"amount\">
              ".pretty_amount($subsidy["requested_amount"])." <i class=\"fa fa-fw fa-euro\"></i>
            </p>
            <p class=\"text\">
              ".$subsidy["purpose"]."
            </p>
          </div>
        </div>"
      );
    }
  ?>
</div>
