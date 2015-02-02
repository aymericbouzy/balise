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
        echo link_to(
          path("edit", "wave", $wave["id"], binet_prefix($wave["binet"], $wave["term"])),
          "<div class=\"round-button grey-background opanel\">
            <i class=\"fa fa-fw fa-edit anim\"></i>
            <span>Modifier</span>
          </div>"
        );
        echo link_to(
        path("show", "wave", $wave["id"], binet_prefix($wave["binet"], $wave["term"])),
          "<div class=\"round-button grey-background opanel\">
            <i class=\"fa fa-fw fa-bookmark-o anim\"></i>
            <span>Statistiques</span>
          </div>"
        );
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
    <span class="submission-date">
      Demandes avant le :<br/>
      <?php echo pretty_date($wave["submission_date"]); ?>
    </span>
    <span class="validity-date">
      Limite de validité :<br/>
      <?php echo pretty_date($wave["expiry_date"]); ?>
    </span>
  </div>
  <?php
    foreach (select_requests(array("wave" => $wave["id"])) as $request) {
      $request = select_request($request["id"], array("id", "state", "binet", "term", "requested_amount"));
      echo link_to(
        path("show", "request", $request["id"], binet_prefix($request["binet"], $request["term"])),
        "<div class=\"sh-wa-request opanel\">
          <p class=\"icon\">
            ".($request["state"] == "sent" ? "<i class=\"fa fa-3x fa-times\"></i>" : "<i class=\"fa fa-3x fa-check\"></i>")."
          </p>
          <p class=\"binet\">
            ".pretty_binet_term($request["binet"]."/".$request["term"])."
          </p>
          <p class=\"amount\">
            ".pretty_amount($request["requested_amount"])." <i class=\"fa fa-euro\"></i>
          </p>
        </div>"
      );
    }
  ?>
</div>
