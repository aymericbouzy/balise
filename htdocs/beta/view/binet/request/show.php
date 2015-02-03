<script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
<div class="show-container">
  <div class="sh-plus <?php echo array("rough_draft" => "grey", "sent" => "orange", "reviewed" => "orange", "accepted" => "green", "rejected" => "red")[$request["state"]]; ?>-background opanel">
    <i class="fa fa-fw fa-<?php echo array("rough_draft" => "question", "sent" => "question", "reviewed" => "question", "accepted" => "check", "rejected" => "times")[$request["state"]]; ?>"></i>
    <div class="text">
      <?php
        switch ($request["state"]) {
          case "rough_draft":
          echo "Brouillon";
          break;
          case "accepted":
          echo "Acceptée";
          break;
          case "rejected":
          echo "Refusée";
          break;
          default:
          if (in_array($request["state"], array("sent", "reviewed"))) {
            echo "Envoyée";
          }
        }
      ?>
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
      if (status_admin_binet($request["wave"]["binet"], $request["wave"]["term"])) {
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
