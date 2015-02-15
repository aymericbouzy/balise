<script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
<div class="show-container">
  <?php $request_state = request_state($request["state"]); ?>
  <div class="sh-plus <?php echo $request_state["color"]; ?>-background opanel">
    <i class="fa fa-fw fa-<?php echo $request_state["icon"] ?>"></i>
    <div class="text">
      <?php echo $request_state["name"]; ?>
    </div>
  </div>
  <div class="sh-actions">
    <?php
      if (has_editing_rights($binet, $term)) {
        switch ($request["state"]) {
          case "rough_draft":
          echo button(path("edit", "request", $request["id"], binet_prefix($binet, $term)), "Modifier", "edit", "grey");
          echo button(path("send", "request", $request["id"], binet_prefix($binet, $term), array(), true), "Soumettre", "paper-plane", "green");
          break;
        }
      }
      if (has_editing_rights($request["wave"]["binet"], $request["wave"]["term"])) {
        if (in_array($request["state"], array("sent", "reviewed"))) {
          echo button(path("review", "request", $request["id"], binet_prefix($binet, $term)), "Etudier", "bookmark", "grey");
        }
      }
    ?>
	</div>
  <div class="sh-title opanel">
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
    if (has_viewing_rights($current_binet["id"], $current_binet["current_term"])) {
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
    <div class="sh-bin-stats<?php echo clean_string($suffix); ?> light-blue-background opanel" id="current_term">
      <?php echo $content; ?>
    </div>
  <?php
    foreach (select_subsidies(array("request" => $request["id"])) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "budget", "requested_amount", "purpose"));
      $budget = select_budget($subsidy["budget"], array("id", "label", "binet", "term"));
      ob_start();
      echo "<div class=\"header\"><span class=\"name\">".$budget["label"]."</span></div>";
      echo "<div class=\"content\">
        <p class=\"amount\">
        ".pretty_amount($subsidy["requested_amount"])." <i class=\"fa fa-fw fa-euro\"></i>
        </p>
        <p class=\"text\">
        ".$subsidy["purpose"]."
        </p>
        </div>";
      $caption = "<div class=\"sh-req-budget opanel\">".ob_get_clean()."</div>";
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
