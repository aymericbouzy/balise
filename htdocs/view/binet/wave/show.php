<div class="show-container">
  <div class="sh-plus opanel">
    <!-- TODO : fa-check vague en cours
                fa-times demande vague terminée-->
    <i class="fa fa-fw fa-check"></i>
    <div class="text"> <!-- TODO Statut de la vague --> </div>
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
      Demandes avant le :
      <?php echo pretty_date($wave["submission_date"]); ?>
    </span>
    <span class="validity-date">
      Limite de validité :
      <?php echo pretty_date($wave["expiry_date"]); ?>
    </span>
  </div>
  <?php
    foreach ($select_requests(array("wave" => $wave["id"])) as $request) {
      $request = select_request($request["id"], array("id", "granted", "binet", "term", "requested_amount"));
      echo link_to(
        path("review", "request", $request["id"], binet_prefix($request["binet"], $request["term"])),
        "<div class=\"sh-wa-request opanel\">
          <p class="icon">
            ".($request["granted"] ? "<i class=\"fa fa-3x fa-check\"></i>" : "<i class=\"fa fa-3x fa-times\"></i>")."
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
