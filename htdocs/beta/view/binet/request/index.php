<div id="index-wrapper">
  <div class="panel opanel light-blue-background">
    <div class="title">
      Informations générales
    </div>
    <div class="content">
      TODO
    </div>
  </div>
  <?php
    foreach($requests as $request) {
    $request_state = request_state($request["state"]);
    $subsidies = select_subsidies(array("request" => $request["id"]));
    ?>
    <!-- !"id", "budget", !"answer", !"sent", !"wave", !"state",!"requested_amount", !"granted_amount", !"used_amount" -->
    <div class="panel opanel light-blue-background">
      <div class="title-small">
        <?php echo pretty_wave($request["wave"]); ?>
      </div>
      <div class="content">
        <div class="state-indicator <?php echo $request_state["color"]; ?>-background">
          <i class="fa fa-fw fa-<?php echo $request_state["icon"]; ?>"></i>
        </div>
        <div class="request-infos">
          <span><?php echo ($request["sent"] == 0 ? "" : pretty_amount($request["granted_amount"])." accordés / ").
            pretty_amount($request["requested_amount"])." demandés." ?></span>
          <span><?php echo ($request["state"] == "accepted" ? pretty_amount($request["used_amount"])." utilisés." : ($request["state"] == "rejected" ? "Subventions refusées" : "")); ?></span>
        </div>
      <div class="subsidies panel inside">
        <div class="title-small">
          Budgets subventionnés
        </div>
        <div class="content light-blue-background">
        <?php foreach($subsidies as $subsidy){
          $subsidy = select_subsidy($subsidy["id"],array("id","budget","purpose","requested_amount","used_amount","granted_amount"));
          ob_start();
          echo "<div class=\"subsidy\">";
          echo "<span>".pretty_budget($subsidy["budget"],true,false)."</span>";
          echo "<span class=\"grey-400-background\">".pretty_amount($subsidy["granted_amount"])."/".pretty_amount($subsidy["requested_amount"])."</span>";
          echo "<span>".(($subsidy["used_amount"]>0) ? pretty_amount($subsidy["used_amount"],false) : "")."</span>";
          echo "</div>";
          echo ob_get_clean();
         } ?>
        </div>
      </div>
      </div>
    </div>
  <?php } ?>
</div>
