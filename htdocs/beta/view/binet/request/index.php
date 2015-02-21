<div id="index-wrapper">
  <div class="panel opanel light-blue-background">
    <div class="title">
      Informations générales
    </div>
    <div class="content">
      <div id="request-count">
        <!-- Requêtes non-envoyées -->
        <span class="<?php echo request_state("rough_draft")["color"];?>-background">
           <?php echo text_tune_with_amount(count($rough_drafts),"brouillon"); ?>
        </span>
        <!-- Requêtes envoyées : en traitement par le binet subventionneur -->
        <span class="<?php echo request_state("sent")["color"];?>-background white-text">
           <?php echo count($sent_requests); ?> en attente
        </span>
        <!-- Requêtes acceptées / requêtes traitées -->
        <span class="<?php echo request_state("accepted")["color"];?>-background">
          <?php echo text_tune_with_amount(count($accepted_requests),"acceptée"); ?> /
          <?php echo count($published_requests); ?> au total
        </span>
      </div>
      <div id="amounts">
        <?php
          echo minipane("total", "Subventions reçues / demandées", $binet_term["subsidized_amount_granted"], $binet_term["subsidized_amount_requested"]);
          echo minipane("use", "Subventions utilisées / reçues", $binet_term["subsidized_amount_used"], $binet_term["subsidized_amount_granted"]);
          echo minipane("requested", "Demandé sur brouillon / en attente", $binet_term["amount_requested_in_rough_drafts"], $binet_term["amount_requested_in_sent"]);
        ?>
      </div>
    </div>
  </div>

  <!-- Rough drafts -->
  <?php
    if (sizeOf($rough_drafts) > 0) {
      ?>
      <div class="panel opanel">
        <div class="title">
          Brouillons
        </div>
      </div>
      <?php
      foreach($rough_drafts as $rough_draft){
        $draft_state = request_state("rough_draft");
        $rough_draft = select_request($rough_draft["id"], array("id", "wave", "requested_amount"));
        $subsidies = select_subsidies(array("request" => $rough_draft["id"]));
        ?>
        <div class="panel opanel light-blue-background">
          <div class="actions">
            <?php
              echo link_to(path("show", "request", $rough_draft["id"], binet_prefix($binet, $term)),
                "<i class=\"fa fa-fw fa-eye\"></i> Voir la requête",array("class"=>"action-on-request btn"));
              echo link_to(path("send", "request", $rough_draft["id"], binet_prefix($binet, $term), array(), true),
                "<i class=\"fa fa-fw fa-send\"></i> Envoyer",array("class"=>"action-on-request btn-success btn"));
              echo link_to(path("delete", "request", $rough_draft["id"], binet_prefix($binet, $term), array(), true),
                "<i class=\"fa fa-fw fa-trash\"></i> Supprimer",array("class"=>"action-on-request btn-danger btn"));
            ?>
          </div>
          <div class="title-small">
            <?php echo pretty_wave($rough_draft["wave"]); ?>
          </div>
          <div class="content">
            <div class="state-indicator <?php echo $draft_state["color"]; ?>-background">
              <i class="fa fa-fw fa-<?php echo $draft_state["icon"]; ?>"></i>
            </div>
            <div class="request-infos">
              <span><?php echo pretty_amount($rough_draft["requested_amount"], false, true); ?> demandés</span>
            </div>
            <div class="subsidies panel inside">
              <div class="content light-blue-background">
                <?php
                  foreach($subsidies as $subsidy){
                      $subsidy = select_subsidy($subsidy["id"], array("id","budget", "purpose","requested_amount"));
                      echo "<span class=\"pill\">".
                      pretty_budget($subsidy["budget"]).
                      " <i>".pretty_amount($subsidy["requested_amount"])."</i></span>";
                  }
                ?>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
    }
  ?>

  <!-- Requests -->
  <div class="panel opanel">
    <div class="title">
      Requêtes par vague de subvention
    </div>
  </div>
  <?php
    foreach(select_requests(array("binet" => $binet, "term" => $term)) as $request) {
      $request = select_request($request["id"], array("id", "state", "wave", "requested_amount", "used_amount", "granted_amount"));
      $wave = select_wave($request["wave"],array("id","binet","term"));
      $request_state = request_state($request["state"],has_editing_rights($wave["binet"], $wave["term"]));
      ?>
      <div class="panel opanel light-blue-background">
        <div class="actions">
          <?php echo link_to(path("show", "request", $request["id"], binet_prefix($binet, $term)),
            "<i class=\"fa fa-fw fa-eye\"></i> Voir la demande",array("class"=>"btn action-on-request"));?>
        </div>
        <div class="title-small">
          <?php echo pretty_wave($request["wave"]); ?>
        </div>
        <div class="content">
          <div class="state-indicator <?php echo $request_state["color"]; ?>-background">
            <i class="fa fa-fw fa-<?php echo $request_state["icon"]; ?>"></i>
          </div>
          <div class="request-infos">
            <span><?php echo ($request["sent"] == 0 ? "" : pretty_amount($request["granted_amount"],false)." accordés / ").
              pretty_amount($request["requested_amount"],false)." demandés." ?></span>
            <span><?php echo ($request["state"] == "accepted" ? pretty_amount($request["used_amount"],false)." utilisés." : ($request["state"] == "rejected" ? "Subventions refusées" : "")); ?></span>
          </div>
          <?php
            if ($request["state"] != "rejected") {
              ?>
              <div class="subsidies panel inside">
                <div class="title-small">
                  Budgets subventionnés
                </div>
                <div class="content light-blue-background">
                  <?php
                    foreach(select_subsidies(array("request" => $request["id"])) as $subsidy){
                      $subsidy = select_subsidy($subsidy["id"],array("id","budget","purpose","requested_amount","used_amount","granted_amount"));
                      ?>
                      <div class="subsidy">
                        <span><?php echo pretty_budget($subsidy["budget"], true, false); ?></span>
                        <span class="grey-400-background"><?php echo pretty_amount($subsidy["granted_amount"])."/".pretty_amount($subsidy["requested_amount"]); ?></span>
                        <span><?php echo $subsidy["used_amount"] > 0 ? pretty_amount($subsidy["used_amount"], false) : ""; ?></span>
                      </div>
                      <?php
                    }
                  ?>
                </div>
              </div>
              <?php
            }
          ?>
        </div>
      </div>
      <?php
    }
  ?>
</div>
