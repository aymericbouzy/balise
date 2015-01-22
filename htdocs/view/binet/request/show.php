<div class="show-container">
  <div class="sh-title">
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
        "<div class=\"sh-req-budget\">
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
