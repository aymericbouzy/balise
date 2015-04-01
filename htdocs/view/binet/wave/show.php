<?php $subsidizer_can_study = in_array($wave["state"], array("deliberation", "submission")); ?>
<div class = "sidebar-present">
  <div class="show-container">
    <?php $wave_state = wave_state($wave["state"]); ?>
    <div class="sh-plus <?php echo $wave_state["color"]; ?>-background shadowed">
      <i class="fa fa-fw fa-<?php echo $wave_state["icon"]; ?>"></i>
      <div class="text">
        <?php echo $wave_state["name"]; ?>
      </div>
    </div>
    <div class="sh-actions">
  		<?php
        if (has_editing_rights(binet, term)) {
          echo button(path("edit", "wave", $wave["id"], binet_prefix(binet, term)), "Modifier", "edit", "blue");
          if (is_openable($wave["id"])) {
            echo button(path("open", "wave", $wave["id"], binet_prefix(binet, term), array(), true), "Ouvrir", "share", "green");
          }
          if (is_publishable($wave["id"])) {
            echo button(path("publish", "wave", $wave["id"], binet_prefix(binet, term), array(), true), "Publier", "share", "green");
          }
        }
      ?>
  	</div>
    <div class="sh-title shadowed">
      <div class="logo">
        <i class="fa fa-5x fa-star"></i>
      </div>
      <div class="text">
        <p class="main">
          <?php echo pretty_binet($wave["binet"]); ?>
        </p>
        <p class="sub">
          <?php echo pretty_wave($wave["id"], false); ?>
        </p>
      </div>
    </div>
    <div class="sh-wa-dates shadowed">
      <span id="submission-date">
        Demandes avant le :<br/>
        <?php echo pretty_date($wave["submission_date"]); ?>
      </span>
      <span id="validity-date">
        Limite de validité :<br/>
        <?php echo pretty_date($wave["expiry_date"]); ?>
      </span>
    </div>
    <div class="panel grey-background shadowed">
      <div class="content">
        <?php echo $wave["description"]; ?>
      </div>
    </div>
    <div class="panel green-background shadowed">
      <div class="content white-text">
        <?php echo $wave["question"]; ?>
      </div>
    </div>
    <?php
      $wave_explanation = is_empty($wave["explanation"]) && has_editing_rights(binet, term) ? "<i>Aucun message promo associé à la publication de la vague renseigné</i>" : $wave["explanation"];
      if (in_array($wave["state"], array("distribution", "closed")) && !is_empty($wave_explanation)) {
        ?>
        <div class="panel blue-background shadowed">
          <div class="content white-text">
            <?php echo $wave_explanation; ?>
          </div>
        </div>
        <?php
      }
    ?>
    <div id="requests">
      <?php
        $requests = select_requests(array("wave" => $wave["id"]));
        foreach ($requests as $request) {
          $request = select_request($request["id"], array("id", "state", "binet", "term", "requested_amount"));
          $request_state = request_state($request["state"], has_viewing_rights(binet, term));
          ob_start();
          ?>
          <div class="sh-wa-request shadowed">
            <p class="marker <?php echo $request_state["color"]; ?>-background"></p>
            <p class="icon"><i class=\"fa fa-fw fa-<?php echo $request_state["icon"]; ?>"></i></p>
            <?php echo insert_tooltip("<p class=\"date\">".pretty_date($request["sending_date"])."</p>", "Date de réception"); ?>
            <p class="binet"><?php echo pretty_binet_term($request["binet"]."/".$request["term"], false); ?></p>
            <p class="amount">
              <?php echo (has_viewing_rights(binet, term) ?
                pretty_amount($request["granted_amount"], false)." / ".pretty_amount($request["requested_amount"], false) :
                ($subsidizer_can_study ? "0" : pretty_amount($request["granted_amount"], false)));
              ?>
              <i class="fa fa-euro"></i>
            </p>
          </div>
          <?php
          if (has_request_viewing_rights($request["id"])) {
            $link_to_path = path($subsidizer_can_study && has_editing_rights(binet, term) ? "review" : "show", "request", $request["id"], binet_prefix($request["binet"], $request["term"]));
          } else {
            $link_to_path = path("show", "binet", $request["binet"]);
          }
          echo link_to(
            $link_to_path,
            ob_get_clean(),
            array("goto" => true)
          );
        }
      ?>
      <div class="sh-wa-stats-container shadowed2">
        <div class="sh-wa-stats">
          <?php
          if (has_viewing_rights(binet, term)) {
            ?>
            <div class="item blue-background">
              Montant total demandé :<br> <?php echo pretty_amount($wave["requested_amount"], false, true); ?>
              <?php echo " / ".pretty_amount($wave["amount"], false, true)." à répartir"; ?>
            </div>
            <?php
          }
          ?>
          <div class="item green-background">
            <?php
            if ((has_viewing_rights(binet, term) && $subsidizer_can_study) || (!$subsidizer_can_study && !has_viewing_rights(binet, term))) {
              echo "Montant total accordé :<br> ".pretty_amount($wave["granted_amount"], false, true);
            } else if (has_viewing_rights(binet, term) && !$subsidizer_can_study) {
              echo "Montant total utilisé :<br> ".pretty_amount($wave["used_amount"], false, false)." / ".pretty_amount($wave["granted_amount"], false, true)." accordé.";
            } else {
              echo "Montant total accordé : <br> <i>non divulgé pour l'instant</i>";
            }
            ?>
          </div>
          <div class="item teal-background">
            <?php
            if(has_viewing_rights(binet, term)) {
              if($subsidizer_can_study){
                echo "Demandes traitées :<br> ".$wave["requests_reviewed"]." / ".$wave["requests_received"]." demandes";
              } else {
                echo "Demandes acceptées :<br> ".$wave["requests_accepted"]." / ".$wave["requests_received"]." demandes";
              }
            }
            else {
              if($subsidizer_can_study){
                echo "Demandes reçues :<br> ".$wave["requests_received"];
              } else {
                echo "Demandes acceptées :<br> ".$wave["requests_accepted"]." / ".$wave["requests_received"]." demandes";
              }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
