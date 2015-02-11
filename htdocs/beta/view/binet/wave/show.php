<div class = "sidebar-present">
  <script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
  <div class="show-container">
    <div class="sh-plus <?php echo $wave["state"] == "closed" ? "red" : "green"; ?>-background opanel">
      <i class="fa fa-fw fa-<?php echo $wave["state"] == "closed" ? "times" : "check"; ?>"></i>
      <div class="text">
        <?php
          $publishable = false;
          switch ($wave["state"]) {
            case "submission":
            echo "Demandes en cours";
            break;
            case "deliberation":
            echo "Etude des demandes";
            $publishable = true;
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
          if($publishable){
            echo button(
              path("edit", "wave", $wave["id"], binet_prefix($wave["binet"], $wave["term"])),
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
    <div class="panel green-background white-text opanel">
      <div class="content">
        <?php echo $wave["question"]; ?>
      </div>
    </div>
    <div id="requests">
      <?php
        $total_reviewed_requests = 0;
        $requests = select_requests(array("wave" => $wave["id"]));
        foreach ($requests as $request) {
          $request = select_request($request["id"], array("id", "state", "binet", "term", "requested_amount"));
          if($request["state"] == "reviewed"){
            $total_reviewed_requests += 1;
          }
          ob_start();
          echo "<p class=\"marker".(in_array($request["state"], array("sent", "rejected")) ? " red" : " green")."-background\" ></p>";
          echo "<p class=\"icon\">".(in_array($request["state"], array("reviewed","accepted","rejected")) ? "<i class=\"fa fa-fw fa-check\"></i>" : "<i class=\"fa fa-fw fa-times\"></i>")."</p>";
          echo "<p class=\"binet\">".link_to(
              path("", "binet", binet_term_id($request["binet"], $request["term"])),
              pretty_binet_term($request["binet"]."/".$request["term"],false))."</p>";
          echo "<p class=\"amount\">".pretty_amount($request["granted_amount"],false)." / ".pretty_amount($request["requested_amount"],false)." <i class=\"fa fa-euro\"></i></p>";

          echo link_to(
            path(($wave["state"] == "deliberation" || $wave["state"] == "submission" )  ? "review" : "show", "request", $request["id"], binet_prefix($request["binet"], $request["term"])),
              "<div>".ob_get_clean()."</div>",
              array("goto" => true,"class"=>"sh-wa-request opanel")
          );
        }
      ?>
      <div class="sh-wa-stats opanel2">
        <div class="item blue-background">
          Montant total demandé : <br> <?php echo pretty_amount($wave["requested_amount"],false,true);?>
        </div>
        <div class="item green-background">
          Montant total accordé : <br> <?php echo pretty_amount($wave["granted_amount"],false,true);?>
        </div>
        <div class="item teal-background">
          Demandes traitées : <br> <?php echo $total_reviewed_requests." / ".sizeOf($requests)." demandes";?>
        </div>
      </div>
    </div>
  </div>
</div>
