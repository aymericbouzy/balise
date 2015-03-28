<script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
<div class="show-container">
  <?php $request_state = request_state($request["state"],has_editing_rights($request["wave"]["binet"], $request["wave"]["term"])); ?>
  <div class="sh-plus <?php echo $request_state["color"]; ?>-background shadowed">
    <i class="fa fa-fw fa-<?php echo $request_state["icon"] ?>"></i>
    <div class="text">
      <?php echo $request_state["name"]; ?>
    </div>
  </div>
  <div class="sh-actions">
    <?php
      if (has_editing_rights($binet, $term)) {
        if (is_editable($request["id"])) {
          echo button(path("edit", "request", $request["id"], binet_prefix($binet, $term)), "Modifier", "edit", "grey");
        }
        if (is_sendable($request["id"])) {
          echo button(path("send", "request", $request["id"], binet_prefix($binet, $term), array(), true), "Soumettre", "paper-plane", "green");
        }
      }
      if (has_editing_rights($request["wave"]["binet"], $request["wave"]["term"])) {
        if (in_array($request["state"], array("sent", "reviewed"))) {
          echo button(path("review", "request", $request["id"], binet_prefix($binet, $term)), "Etudier", "bookmark", "grey");
        }
      }
    ?>
	</div>
  <div class="panel shadowed">
    <div class="title">
      Demande de subventions
    </div>
  </div>
  <div class="sh-title shadowed">
    <div class="logo">
      <i class="fa fa-5x fa-money"></i>
    </div>
    <div class="text">
      <p class="main">
        <?php echo pretty_binet_term($binet."/".$term); ?>
      </p>
      <p class="sub">
        <?php echo pretty_wave($request["wave"]["id"], false); ?>
      </p>
    </div>
  </div>
    <?php
    ob_start();
    if (has_viewing_rights($current_binet["id"], $current_binet["current_term"])
    		&& in_array($request['wave']['state'],array("deliberation","submission"))) {
      echo minipane("income", "Recettes", $current_binet["real_income"], $current_binet["expected_income"]);
      echo minipane("spending", "Dépenses", $current_binet["real_spending"], $current_binet["expected_spending"]);
      echo minipane("balance", "Equilibre", $current_binet["real_balance"], $current_binet["expected_balance"]);
      $suffix = "";
    } else {
      $suffix = "_std";
    }
    echo minipane("subsidies_granted".$suffix, "Subventions accordées", $current_binet["subsidized_amount_granted"], NULL);
    echo minipane("subsidies_used".$suffix, "Subventions utilisées", $current_binet["subsidized_amount_used"], NULL);
    $content = ob_get_clean();
    ?>
  <div class="sh-bin-stats<?php echo clean_string($suffix); ?> light-blue-background shadowed" id="current_term">
    <?php echo $content; ?>
  </div>
  <?php if( has_viewing_rights($binet,$term) || has_editing_rights($request["wave"]["binet"], $request["wave"]["term"])){
    ?>
    <div class="panel shadowed light-blue-background">
      <?php
        $caption = "<div class=\"title-small\"> Opérations liées à cette subvention ".
        "<i class=\"fa fa-fw fa-chevron-down\"></i></div>";
        echo make_collapse_control($caption, "operation_subsidies_collapse");
      ?>
      <div class="collapse" id="operation_subsidies_collapse">
        <div class="content container-fluid">
          <?php
          foreach(select_operations_request($request["id"]) as $operation){
            $operation = select_operation($operation,array("id","comment","amount"));
            echo "<div class=\"row\">".
            				"<div class=\"col-sm-8\">".pretty_operation($operation["id"], true, true)."</div>".
            				"<div class=\"col-sm-4\">".pretty_amount($operation["amount"],false,true)."</div>".
            		"</div>";
          }
          ?>
        </div>
      </div>
    </div>
    <?php
    }
  ?>
  <?php
    foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "budget", "requested_amount", "granted_amount", "used_amount", "purpose"));
      $budget = select_budget($subsidy["budget"], array("id", "label", "binet", "term"));
      ob_start();
      echo "<div class=\"header\"><span class=\"name\">".$budget["label"]."</span></div>";
      echo "<div class=\"content\">
        <p class=\"amount\">".
        (has_viewing_rights($binet,$term) ? pretty_amount($subsidy["used_amount"])." utilisés, " : "").
        pretty_amount($subsidy["granted_amount"])." accordés".
        (has_viewing_rights($binet,$term) ? ", ".pretty_amount($subsidy["requested_amount"])." demandés" :"")."</i>
        </p>
        <p class=\"text\">".
        		$subsidy["purpose"].
       "</p>
        </div>";
      $caption = "<div class=\"sh-req-budget shadowed\">".ob_get_clean()."</div>";
      echo has_viewing_rights($binet, $term) ?
        link_to(
          path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),
          $caption,
          array("goto" => true)
        ) :
        $caption;
    }
  ?>
</div>
