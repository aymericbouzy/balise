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
          $request = select_request($request["id"], array("id", "state", "binet", "term", "requested_amount", "used_amount"));
          $request_state = request_state($request["state"], has_viewing_rights(binet, term));
          ob_start();
          ?>
          <div class="sh-wa-request shadowed">
            <p class="marker <?php echo $request_state["color"]; ?>-background"></p>
            <p class="icon"><i class=\"fa fa-fw fa-<?php echo $request_state["icon"]; ?>"></i></p>
            <?php echo insert_tooltip("<p class=\"date\">".pretty_date($request["sending_date"])."</p>", "Date de réception"); ?>
            <p class="binet"><?php echo pretty_binet_term(term_id($request["binet"], $request["term"]), false); ?></p>
            <p class="amount">
              <?php echo (has_viewing_rights(binet, term) ?
                pretty_amount($request["granted_amount"], false)." / ".pretty_amount($request["requested_amount"], false) :
                ($subsidizer_can_study ? "0" : pretty_amount($request["granted_amount"], false)));
              ?>
              <i class="fa fa-euro"></i>
            </p>
            <?php
              if(has_viewing_rights(binet, term) && $request["granted_amount"] > 0) {
                $content = "<p class=\"amount-used ".request_used_amount_status($request)."-background\">".
                  pretty_amount($request["used_amount"], false, true);
                echo insert_tooltip($content,"Montant utilisé");
              }
            ?>
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
        <?php
        ob_start();
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
        <?php
        if (has_viewing_rights(binet, term)) {
          echo "<span class=\"message\"><i class=\"fa fa-fw fa-eye\"></i> Exporter dans un tableau</span>";
          echo modal_toggle("subsidies-export-toggle", ob_get_clean(), "sh-wa-stats", "subsidies-export");
        } else {
          echo "<div class=\"sh-wa-stats\">".ob_get_clean()."</div>";
        }
        ?>
      </div>
    </div>
  </div>
</div>

<?php
if (has_viewing_rights(binet, term)) {
  ob_start();
  ?>
  <table class="table table-bordered table-hover table-small-char">
    <thead>
      <tr>
        <td>Binet</td>
        <td>Promo</td>
        <td>Montant demandé</td>
        <td>Subventions déjà utilisées</td>
        <td>Subventions déjà accordées</td>
        <td>Subventions utilisées par la promo précédente</td>
      </tr>
    </thead>
    <tbody class="list">
    <?php
    $requests = select_requests(array("wave" => $wave["id"]));
    foreach ($requests as $request) {
      $request = select_request($request["id"], array("id", "binet", "term", "requested_amount"));
      $binet_term = select_term_binet(term_id($request["binet"], $request["term"]), array("subsidized_amount_used", "subsidized_amount_granted"));
      $previous_binet_term = select_term_binet(term_id($request["binet"], $request["term"] - 1), array("subsidized_amount_used"));
      ?>
      <tr>
        <td><?php echo pretty_binet($request["binet"], false, false); ?></td>
        <td><?php echo $request["term"]; ?></td>
        <td><?php echo pretty_amount($request["requested_amount"], false); ?></td>
        <td><?php echo pretty_amount($binet_term["subsidized_amount_used"], false); ?></td>
        <td><?php echo pretty_amount($binet_term["subsidized_amount_granted"], false); ?></td>
        <td><?php echo is_empty($previous_binet_term) ? "" : pretty_amount($previous_binet_term["subsidized_amount_used"], false); ?></td>
      </tr>
      <?php
    }
    ?>
    </tbody>
  </table>
  <?php
  echo modal("subsidies-export", ob_get_clean(), array("title" => "Tableau résumé de toutes les demandes de subventions"));
}
?>
