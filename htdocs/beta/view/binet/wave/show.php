<div class = "sidebar-present">
  <script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
  <div class="show-container">
    <div class="sh-plus <?php echo $wave["state"] == "closed" ? "red" : "green"; ?>-background opanel">
      <i class="fa fa-fw fa-<?php echo $wave["state"] == "closed" ? "times" : "check"; ?>"></i>
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
            echo "Ouverte";
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
    <div id="requests">
      <?php
        $total_requested_amount = 0;
        $total_reviewed_requests = 0;
        $total_granted_amount = 0;
        foreach (select_requests(array("wave" => $wave["id"])) as $request) {
          $request = select_request($request["id"], array("id", "state", "binet", "term", "requested_amount"));
          $total_requested_amount += $request["requested_amount"];
          if($request["state"] == "reviewed"){
            $total_reviewed_requests += 1;

            if($request["state"] == "accepted"){
              $total_granted_amount += get_granted_amount_request($request["id"]);
            }
          }
          echo link_to(
            path("review", "request", $request["id"], binet_prefix($request["binet"], $request["term"])),
            "<div class=\"sh-wa-request opanel\">
              <p class=\"marker
                ".(($request["sent"] != 1)? " red" : " green")."-background\" ></p>
              <p class=\"icon\">
                ".($request["state"] != "reviewed" ? "<i class=\"fa fa-fw fa-times\"></i>" : "<i class=\"fa fa-fw fa-check\"></i>")."
              </p>
              <p class=\"binet\">
                ".pretty_binet_term($request["binet"]."/".$request["term"])."
              </p>
              <p class=\"amount\">
                ".pretty_amount($request["requested_amount"],false)." <i class=\"fa fa-euro\"></i>
              </p>
            </div>",
            array("goto" => true)
          );
        }
      ?>
      <div class="sh-wa-stats opanel2">
        <div class="item teal-background">
          Montant total demandé : <br> <?php echo pretty_amount($total_requested_amount,false,true);?>
        </div>
        <div class="item purple-background">
          Montant total accordé : <br> <?php echo pretty_amount($total_granted_amount,false,true);?>
        </div>
        <div class="item green-background">
          Demandes traitées : <br> <?php echo $total_reviewed_requests;?>
        </div>
      </div>
    </div>
  </div>
</div>
