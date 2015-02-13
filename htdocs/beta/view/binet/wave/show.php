<?php $subsidizer_can_study = in_array($wave["state"], array("deliberation", "submission")); ?>
<div class = "sidebar-present">
  <div class="show-container">
    <?php $wave_state_to_color = array("submission" => "blue", "deliberation" => "teal", "distribution" => "green", "closed" => "grey");
          $wave_state_to_icon = array("submission" => "crosshairs", "deliberation" => "cogs", "distribution" => "money", "closed" => "times");
    ?>
    <div class="sh-plus <?php echo $wave_state_to_color[$wave["state"]]; ?>-background opanel">
      <i class="fa fa-fw fa-<?php echo $wave_state_to_icon[$wave["state"]]; ?>"></i>
      <div class="text">
        <?php
          switch ($wave["state"]) {
            case "submission":
            echo "Demandes en cours";
            break;
            case "deliberation":
            echo "Etude des demandes";
            break;
            case "distribution":
            echo "Emise";
            break;
            case "closed":
            echo "Fermée";
            break;
          }
        ?>
      </div>
    </div>
    <div class="sh-actions">
  		<?php
        if (has_editing_rights($binet, $term)) {
          echo button(
            path("edit", "wave", $wave["id"], binet_prefix($wave["binet"], $wave["term"])),
            "Modifier","edit","blue");
          if (is_publishable($wave["id"])) {
            echo button(
              path("publish", "wave", $wave["id"], binet_prefix($wave["binet"], $wave["term"]),array(),true),
              "Publier","share","green");
          }
        }
      ?>
  	</div>
    <div class="sh-title opanel">
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
    <div class="sh-wa-dates opanel">
      <span id="submission-date">
        Demandes avant le :<br/>
        <?php echo pretty_date($wave["submission_date"]); ?>
      </span>
      <span id="validity-date">
        Limite de validité :<br/>
        <?php echo pretty_date($wave["expiry_date"]); ?>
      </span>
    </div>
    <div class="panel green-background opanel">
      <div class="content white-text">
        <?php echo $wave["question"]; ?>
      </div>
    </div>
    <div id="requests">
      <?php
        $requests = select_requests(array("wave" => $wave["id"]));
        foreach ($requests as $request) {
          $request = select_request($request["id"], array("id", "state", "binet", "term", "requested_amount"));
          ob_start();
          $state_to_color = array("sent" => "orange", "reviewed_accepted" => "green", "reviewed_rejected" => "red", "accepted" => "green", "rejected" => "red");
          $color = !has_viewing_rights($binet, $term) && $subsidizer_can_study ? "grey" : $state_to_color[$request["state"]];
          echo "<p class=\"marker ".$color."-background\" ></p>";
          $state_to_icon = array("sent" => "question", "reviewed_accepted" => "check", "reviewed_rejected" => "times", "accepted" => "check", "rejected" => "times");
          $icon = !has_viewing_rights($binet, $term) && $subsidizer_can_study ? "cogs" : $state_to_icon[$request["state"]];
          echo "<p class=\"icon\"><i class=\"fa fa-fw fa-".$icon."\"></i></p>";
          echo "<p class=\"binet\">".
          ($subsidizer_can_study && has_viewing_rights($request["binet"], $request["term"]) ?
            link_to(path("", "binet", binet_term_id($request["binet"], $request["term"])), pretty_binet_term($request["binet"]."/".$request["term"], false)) :
            pretty_binet_term($request["binet"]."/".$request["term"], false)
          )."</p>";
          echo "<p class=\"amount\">".
          (has_viewing_rights($binet, $term) || !$subsidizer_can_study ? pretty_amount($request["granted_amount"], false)." / " : "").
          pretty_amount($request["requested_amount"], false)." <i class=\"fa fa-euro\"></i></p>";

          echo link_to(
            path($subsidizer_can_study && has_editing_rights($binet, $term) ? "review" : "show", "request", $request["id"], binet_prefix($request["binet"], $request["term"])),
            "<div>".ob_get_clean()."</div>",
            array("goto" => true, "class"=> "sh-wa-request opanel")
          );
        }
      ?>
      <div class="sh-wa-stats opanel2">
        <div class="item blue-background">
          Montant total demandé : <br> <?php echo pretty_amount($wave["requested_amount"], false, true); ?>
        </div>
        <div class="item green-background">
          <?php
          if ((has_viewing_rights($binet, $term) && $subsidizer_can_study) || (!$subsidizer_can_study && !has_viewing_rights($binet, $term))) {
            echo "Montant total accordé : <br> ".pretty_amount($wave["granted_amount"], false, true);
          } else if (has_viewing_rights($binet, $term) && !$subsidizer_can_study) {
            echo "Montant total utilisé : <br> ".pretty_amount($wave["used_amount"], false, false)." / ".pretty_amount($wave["granted_amount"], false, true)." accordé.";
          } else {
            echo "Montant total accordé : <br> <i>non divulgé pour l'instant</i>";
          }
          ?>
        </div>
        <div class="item teal-background">
          <?php
          if(has_viewing_rights($binet, $term)) {
            if($subsidizer_can_study){
              echo "Demandes traitées : <br> ".$wave["requests_reviewed"]." / ".$wave["requests_received"]." demandes";
            } else {
              echo "Demandes acceptées : <br> ".$wave["requests_accepted"]." / ".$wave["requests_received"]." demandes";
            }
          }
          else {
            if($subsidizer_can_study){
              echo "Demandes reçues : <br> ".$wave["requests_received"];
            } else {
              echo "Demandes acceptées : <br> ".$wave["requests_accepted"]." / ".$wave["requests_received"]." demandes";
            }
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
