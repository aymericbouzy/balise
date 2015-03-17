<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/action/show.css" type="text/css">
<div class="show-container">
  <?php $request_state = request_state($request_info["state"],has_editing_rights($request_info["wave"]["binet"], $request_info["wave"]["term"])); ?>
  <div class="sh-plus <?php echo $request_state["color"]; ?>-background shadowed">
    <i class="fa fa-fw fa-<?php echo $request_state["icon"] ?>"></i>
    <div class="text">
      <?php echo $request_state["name"]; ?>
    </div>
  </div>
  <div class="sh-actions">
    <?php
      echo button(path("reject", "request", $request_info["id"], binet_prefix($binet, $term), array(), true), "Refuser", "times", "red");
    ?>
  </div>
  <div class="sh-title shadowed">
    <div class="logo">
      <i class="fa fa-5x fa-money"></i>
      <?php echo insert_tooltip("<span>".pretty_date($request["sending_date"])."</span>","Date de réception de la requête");?>
    </div>
    <div class="text">
      <p class="main">
        <?php echo pretty_binet_term($binet."/".$term); ?>
      </p>
      <p class="sub">
        <?php echo pretty_wave($request_info["wave"]["id"]); ?>
      </p>
    </div>
  </div>
  <div class="panel light-blue-background shadowed">
    <div class="content">
      <?php echo (is_empty($current_binet["description"]) ? "Aucune description pour ce binet" : $current_binet["description"]); ?>
    </div>
  </div>
  <!-- Answer to the wave question -->
  <div class="panel green-background shadowed">
    <div class="content white-text">
      <?php echo $request_info["answer"]; ?>
    </div>
  </div>
  <?php
    ob_start();
    if (has_viewing_rights($current_binet["id"], $current_binet["current_term"])) {
    	echo "<span class=\"reviewForm_infotitle\"> Trésorerie du binet (réel / previsionnel)</span>";
      echo minipane("reviewForm_income", "Recettes", $current_binet["real_income"], $current_binet["expected_income"]);
      echo minipane("reviewForm_spending", "Dépenses", $current_binet["real_spending"], $current_binet["expected_spending"]);
      echo minipane("reviewForm_balance", "Equilibre", $current_binet["real_balance"], $current_binet["expected_balance"]);
      echo "<span class=\"message\"><i class=\"fa fa-fw fa-eye\"></i> Voir l'activité du binet </span>";
    	$content = ob_get_clean();
    	echo link_to(path("",binet_prefix($current_binet["id"], $current_binet["current_term"])),
      	"<div>".$content."</div>",
      	array("class" => "light-blue-background shadowed panel","id" => "current-term", "goto" => true));
    }
    if (!is_empty($previous_binet)) {
      ob_start();
      if (has_viewing_rights($current_binet["id"], $current_binet["current_term"] - 1)) {
      	echo "<span class=\"reviewForm_infotitle\">Trésorerie du binet de la promotion précédente </span>";
        echo minipane("reviewForm_income_old", "Recettes", $previous_binet["real_income"], $previous_binet["expected_income"]);
        echo minipane("reviewForm_spending_old", "Dépenses", $previous_binet["real_spending"], $previous_binet["expected_spending"]);
        echo minipane("reviewForm_balance_old", "Equilibre", $current_binet["real_balance"], $previous_binet["expected_balance"]);
        echo "<span class=\"message\"><i class=\"fa fa-fw fa-eye\"></i> Voir l'activité du binet de la promotion précédente </span>";
      	$content = ob_get_clean();
      	echo link_to(path("",binet_prefix($current_binet["id"], $current_binet["current_term"] - 1)),
          	"<div>".$content."</div>",
          	array("class" => "light-blue-background shadowed panel","id" => "previous-term", "goto" => true));
    }
    }
    ?>
    <div class="panel shadowed">
    	<?php
    		$collapse_title = "<div class=\"title\"> Résumé des subventions passées <i class=\"fa fa-fw fa-chevron-down\"></i></div>";
    		echo make_collapse_control($collapse_title, "subsidies-summary");
    	?>
    </div>
	  <div class="collapse" id="subsidies-summary">
	    <div class="panel light-blue-background shadowed">
	      <div class="title-small">
	        Subventions <?php echo pretty_binet($request_info["wave"]["binet"],false); ?>
	      </div>
	      <div class="content">
	        <?php
	          echo minipane("reviewForm_owner-granted-requested", "Accordées / demandées  ",
	          		$existing_subsidies["granted_amount"],
	          		$existing_subsidies["requested_amount"]);
	          echo minipane("reviewForm_owner-used-available", "Utilisées / disponibles ",
	          		$existing_subsidies["used_amount"],
	          		 $existing_subsidies["granted_amount"] - $existing_subsidies["used_amount"]);
	         	echo minipane("reviewForm_owner-used-granted_old", "Utilisées / accordées l'année dernière",
	         			 $previous_subsidies["used_amount"],
	         			 $previous_subsidies ["granted_amount"]);
	        ?>
	      </div>
	    </div>
	    <div class="panel light-blue-background shadowed">
	    	<div class="title-small">
	     		Total subventions reçues par le binet cette année:
	    	</div>
	    	<div class="content">
	     		<?php
	     			echo minipane("reviewForm_granted-requested", "Accordées / demandées ",
	          		$current_binet["subsidized_amount_granted"],
	          		$current_binet["subsidized_amount_requested"]);
	          echo minipane("reviewForm_used-available", " Utilisées / disponibles",
	          		$current_binet["subsidized_amount_used"], $current_binet["subsidized_amount_available"]);
	        ?>
	    	</div>
	    </div>
	    <div class="panel light-blue-background shadowed" id="previous-term-subsidies" >
	    	<div class="title-small">
	     		Pour l'année dernière:
	     	</div>
	     	<div class="content">
	     		<?php
	     			echo minipane("reviewForm_granted-requested_old", "Accordées / demandées ",
	          		$previous_binet["subsidized_amount_granted"],
	          		$previous_binet["subsidized_amount_requested"]);
	          echo minipane("reviewForm_used-available_old", " Utilisées",
	          		$previous_binet["subsidized_amount_used"],"0");
	        ?>
	    	</div>
	    </div>
		</div>
    <!-- Form -->
    <?php
    foreach (select_subsidies(array("request" => $request_info["id"])) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "budget", "requested_amount", "purpose"));
      $budget = select_budget($subsidy["budget"], array("id", "label", "binet", "term", "real_amount", "amount", "subsidized_amount", "subsidized_amount_granted", "subsidized_amount_used", "subsidized_amount_available"));
      ?>
      <div class="panel light-blue-background shadowed">
        <?php
          echo link_to(
            path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),
            "<div class=\"title\">".$budget["label"]."<span><i class=\"fa fa-fw fa-eye\"></i>  Voir le budget</span></div>",
            array("goto"=>true)
          );
        ?>
        <div class="content">
          <div class="infos table table-responsive">
            <table>
              <thead>
                <tr>
                  <td class="minititle" >Montant demandé</td>
                  <td class="minititle" >Résumé du budget</td>
                  <td class="minititle" >Subventions</td>
                </tr>
              </thead>
              <tbody>
                <tr class="summary">
                  <td rowspan="3" class="amount-requested"><?php echo pretty_amount($subsidy["requested_amount"],false,true); ?></td>
                  <td> Prévisionnel : <?php echo pretty_amount($budget["amount"])?></td>
                  <td> Attendues : <?php echo pretty_amount($budget["subsidized_amount"])?></td>
                </tr>
                <tr class="summary">
                  <td> Réel : <?php echo pretty_amount($budget["real_amount"])?></td>
                  <td> Disponibles : <?php echo pretty_amount($budget["subsidized_amount_available"])?></td>
                </tr>
                <tr class="summary">
                	<td></td>
                  <td>Utilisées : <?php echo pretty_amount($budget["subsidized_amount_used"])?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="granted-amount">
            <?php echo form_input("Montant accordé :", "amount_".$subsidy["id"], $form); ?>
            <?php echo form_input("sous condition", "conditional_".$subsidy["id"], $form); ?>
          </div>
          <div class="purpose green-background white-text">
            <?php echo $subsidy["purpose"]?>
          </div>
          <div class="explanation">
            <?php echo form_input("Explication :", "explanation_".$subsidy["id"], $form); ?>
          </div>
        </div>
      </div>
    <?php
    }
  ?>
  <div class="submit-button">
    <?php echo form_submit_button("Enregistrer"); ?>
  </div>
</div>

