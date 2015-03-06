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
        if (has_editing_rights($binet, $term)) {
          echo button(path("edit", "wave", $wave["id"], binet_prefix($binet, $term)), "Modifier", "edit", "blue");
          if (is_openable($wave["id"])) {
            echo button(path("open", "wave", $wave["id"], binet_prefix($binet, $term), array(), true), "Ouvrir", "share", "green");
          }
          if (is_publishable($wave["id"])) {
            echo button(path("publish", "wave", $wave["id"], binet_prefix($binet, $term), array(), true), "Publier", "share", "green");
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
      if (in_array($wave["state"], array("distribution", "closed")) && !is_empty($wave["explanation"])) {
        ?>
        <div class="panel blue-background shadowed">
          <div class="content white-text">
            <?php echo $wave["explanation"]; ?>
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
          $request_state = request_state($request["state"],has_viewing_rights($binet, $term));
          ob_start();
          echo "<p class=\"marker ".$request_state["color"]."-background\" ></p>";
          echo "<p class=\"icon\"><i class=\"fa fa-fw fa-".$request_state["icon"]."\"></i></p>";
          echo insert_tooltip("<p class=\"date\">".pretty_date($request["sending_date"])."</p>","Date de réception");
          echo "<p class=\"binet\">".pretty_binet_term($request["binet"]."/".$request["term"], false)."</p>";
          echo "<p class=\"amount\">".
          (has_viewing_rights($binet, $term) || !$subsidizer_can_study ? pretty_amount($request["granted_amount"], false)." / " : "").
          pretty_amount($request["requested_amount"], false)." <i class=\"fa fa-euro\"></i></p>";

          echo link_to(
            path($subsidizer_can_study && has_editing_rights($binet, $term) ? "review" : "show", "request", $request["id"], binet_prefix($request["binet"], $request["term"])),
            "<div>".ob_get_clean()."</div>",
            array("goto" => true, "class"=> "sh-wa-request shadowed")
          );
        }
      ?>
      <div class="sh-wa-stats-container shadowed2">
        <div class="sh-wa-stats">
          <div class="item blue-background">
            Montant total demandé :<br> <?php echo pretty_amount($wave["requested_amount"], false, true); ?>
            <?php
            if (has_viewing_rights($binet, $term)) {
              echo " / ".pretty_amount($wave["amount"], false, true)." à répartir";
            }
            ?>
          </div>
          <div class="item green-background">
            <?php
            if ((has_viewing_rights($binet, $term) && $subsidizer_can_study) || (!$subsidizer_can_study && !has_viewing_rights($binet, $term))) {
              echo "Montant total accordé :<br> ".pretty_amount($wave["granted_amount"], false, true);
            } else if (has_viewing_rights($binet, $term) && !$subsidizer_can_study) {
              echo "Montant total utilisé :<br> ".pretty_amount($wave["used_amount"], false, false)." / ".pretty_amount($wave["granted_amount"], false, true)." accordé.";
            } else {
              echo "Montant total accordé : <br> <i>non divulgé pour l'instant</i>";
            }
            ?>
          </div>
          <div class="item teal-background">
            <?php
            if(has_viewing_rights($binet, $term)) {
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
