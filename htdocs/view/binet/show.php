<div class="show-container">
  <?php echo button(path("","binet"),"Aller à la liste des binets","bars","blue",true,"","left"); ?>
  <?php
    if (has_viewing_rights($binet["id"], $binet["current_term"])) {
      ?>
      <div class="sh-plus <?php echo $binet["state"]; ?>-background shadowed">
        <i class="fa fa-fw fa-<?php $color_to_icon = array("green" => "check", "orange" => "warning", "red" => "minus-circle", "grey" => "moon-o"); echo $color_to_icon[$binet["state"]]; ?>"></i>
        <span class="text">Etat du binet</span>
      </div>
      <?php
    }
  ?>
  <div class="sh-actions">
    <?php
      echo button(contact_binet_path($binet["id"]), "Contacter", "paper-plane", "grey");
      if (has_editing_rights($binet["id"], $binet["current_term"]) || is_current_kessier()) {
        echo button(path("edit", "binet", $binet["id"]), "Modifier le binet", "edit", "orange");
      }
      if (is_current_kessier()) {
        if ($binet["subsidy_provider"]) {
          echo button(path("switch_subsidy_provider", "binet", $binet["id"], "", array(), true), "Retirer les droits de subventionneur", "money", "red");
        } else {
          echo button(path("switch_subsidy_provider", "binet", $binet["id"], "", array(), true), "Ajouter les droits de subventionneur", "money", "blue");
        }
        echo button(path("change_term", "binet", $binet["id"]), is_empty($binet["current_term"]) ? "Réactiver le binet" : "Faire la passation", "forward", "green");
      }
    ?>
  </div>
  <div class="sh-title shadowed">
    <div class="logo">
      <i class="fa fa-5x fa-group"></i>
    </div>
    <div class="text">
      <span class="main">
        <?php
          echo pretty_binet($binet["id"], false);
        ?>
      </span>
      <?php
        if (has_viewing_rights($binet["id"], $binet["current_term"]) && $binet["state"] != "grey") {
            echo insert_tooltip(
                link_to(
                  path("", "binet", binet_term_id($binet["id"], $binet["current_term"])),
                    "<i class=\"fa fa-fw fa-eye\"></i>",
                    array("class" => "shadowed0 btn btn-success","id" =>"sh-bin-eye")
                  ),
              "Voir l'activité du binet",
              "left");
        }
      ?>
      <!-- Modal to choose a different term -->
      <?php echo modal_toggle("choose-term", (is_empty($binet["current_term"]) ? "Actuellement inactif" : "Promo ".$binet["current_term"])."<i class=\"fa fa-fw fa-caret-square-o-down\"></i>", "sub shadowed0", "terms"); ?>
    </div>
  </div>
  <?php
  if ($binet["subsidy_provider"]){ ?>
    <div class="panel shadowed light-blue-background">
      <?php
        $html_content = "<div class=\"title-small \"> Comment puis-je récupérer des subventions de ce binet ? <i class=\"fa fa-fw fa-chevron-down\"></i> </div>";
        echo make_collapse_control($html_content,"howToGetSubsisidies_content");
      ?>
      <div class="collapse" id="howToGetSubsisidies_content">
        <div class="content">
          <?php if(has_editing_rights($binet["id"], $binet["current_term"])) {
              echo link_to(path("edit", "binet", $binet["id"]), "<i class=\"fa fa-fw fa-edit\"></i> Modifier", array("class" => "panelAction"));
          }
          echo $binet["subsidy_steps"]; ?>
          </ul>
        </div>
      </div>
    </div>
  <?php
  }
  if (!is_empty($binet["current_term"])) {
    ?>
    <div class="panel light-blue-background shadowed">
      <span class="title">
        Administrateurs
      </span>
      <div class="content">
        <?php
        $admins = select_current_admins($binet["id"]);
        if (!empty($admins)) {
          foreach ($admins as $admin) {
            ?>
            <span class="admin">
              <i class="fa fa-fw fa-user logo"></i>
              <i class="fa fa-fw fa-eye logo"></i>
              <?php echo pretty_student($admin["id"]); ?>
            </span>
            <?php
          }
        } else {
          ?>
          <i style="padding:5px;color:#DD2C00;" class="fa fa-fw fa-warning"></i> Il n'y a aucun administrateur pour ce binet !
          <?php
        }
        if (is_current_kessier()) {
          ?>
          <div class="add">
            <?php
            echo button(path("new", "admin", "", binet_prefix($binet["id"], $binet["current_term"])), "Ajouter un administrateur", "plus", "green", true, "small");
            if (!empty($admins)){
              echo button(path("index", "admin", "", binet_prefix($binet["id"], $binet["current_term"])), "Supprimer un administrateur", "minus", "red", true, "small");
            }
            ?>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="panel shadowed light-blue-background">
    <div class="content">
      <div class="panel-article">
        <?php
          if($binet["description"]!=""){
            echo $binet["description"];
          } else {
            echo "<i> Aucune description. </i>";
          }
        ?>
      </div>
    </div>
  </div>
  <?php
    if ($binet["state"] != "grey") {
      ob_start();
      if (has_viewing_rights($binet["id"], $binet["current_term"])) {
        echo minipane("income", "Recettes", $binet["real_income"], $binet["expected_income"]);
        echo minipane("spending", "Dépenses", $binet["real_spending"], $binet["expected_spending"]);
        echo minipane("balance", "Equilibre", $binet["real_balance"], $binet["expected_balance"]);
        $suffix = "";
      } else {
        $suffix = "_std";
      }
      echo minipane("subsidies_granted".$suffix, "Subventions accordées", $binet["subsidized_amount_granted"], NULL);
      echo minipane("subsidies_used".$suffix, "Subventions utilisées", $binet["subsidized_amount_used"], NULL);
      $content = ob_get_clean();
      ?>
      <div class="panel sh-bin-stats<?php echo clean_string($suffix); ?> light-blue-background shadowed">
        <?php echo $content; ?>
      </div>
      <?php
    }
    if (!empty($waves)) {
      ?>
      <div class="sh-bin-resume light-blue-background shadowed">
        <div class="title">
          Vagues de subventions émises
        </div>
        <?php
          foreach($waves as $wave) {
            ?>
            <div class="line">
              <span class="label"><?php echo pretty_wave($wave["id"]); ?></span>
              <span class="submission date"><?php echo $wave["submission_date"]; ?></span>
              <span class="expiry date"> <?php echo $wave["expiry_date"]; ?></span>
            </div>
            <?php
          }
        ?>
      </div>
      <?php
    }
  ?>
  <?php if(has_viewing_rights($binet['id'],$binet['current_term'])) {?>
	  <div class="panel shadowed light-blue-background">
	  	<?php
	  		$collapse_control_content =" <div class=\"title-small\"> Subventions reçues".
  												"<i class=\"fa fa-fw fa-chevron-down\"></i> </div>";
	  		echo make_collapse_control($collapse_control_content, "subsidies_list");
	  	?>
	  	<div class="collapse" id="subsidies_list">
	  		<div class="content">
	  		<?php
          foreach (select_requests(array("binet" => $binet['id'],"term"=> $binet["current_term"])) as $request) {
          	$request = select_request($request["id"] , array("id","wave","binet","granted_amount"))
						?>
							<div class="panel-line">
								<div class="col-sm-8">
									<?php echo pretty_wave($request['wave']); ?>
								</div>
								<div class="col-sm-3">
									<?php echo pretty_amount($request['granted_amount']); ?>
								</div>
								<div class="col-sm-1">
									<?php echo link_to(path("show","request",$request["id"],binet_prefix($binet['id'],$binet['current_term'])),
											"<i class=\"fa fa-fw fa-eye\"></i>")?>
								</div>
							</div>
						<?php
          }
          ?>
	  		</div>
	  	</div>
	  </div>
  <?php } ?>
</div>
<?php echo modal("terms", "Toutes les promotions du binet", pretty_terms_list($binet["id"])); ?>
