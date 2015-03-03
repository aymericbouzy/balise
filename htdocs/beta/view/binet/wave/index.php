<div id="wave-index-wrapper">
  <div id="wave-index">
    <ul class="list">
      <?php
      foreach ($waves_rough_drafts as $wave) {
        $wave = select_wave($wave["id"], array("id", "name", "submission_date", "expiry_date", "binet", "term", "state", "amount"));
        $wave_state = wave_state("rough_draft");
        ?>
        <li class="content-line-panel-small">
          <?php
          ob_start();
          ?>
          <i class="fa fa-3x fa-money"></i>
          <span class="name"><?php echo pretty_wave($wave["id"], false); ?></span>
          <span class="state <?php echo $wave_state["color"]; ?>-background">
            <?php echo $wave_state["name"]; ?>
          </span>
          <span class="dates">
            <span class="top green-background">
              <?php
              echo pretty_date($wave["submission_date"]);
              ?>
            </span>
            <span class="bottom orange-background">
              <?php
              echo pretty_date($wave["expiry_date"]);
              ?>
            </span>
          </span>
          <span class="amount green-background">
            <?php echo pretty_amount($wave["amount"]); ?>
          </span>
          <?php
          echo link_to(path("show", "wave", $wave["id"],binet_prefix($wave["binet"],$wave["term"])), "<div>".ob_get_clean()."</div>\n", array("class" => "shadowed clickable-main", "goto" => true));
          ?>
        </li>
        <?php
      }
      ?>
    </ul>
    <ul class="list">
      <?php
      foreach ($waves as $wave) {
        $wave = select_wave($wave["id"], array("id", "name", "submission_date", "expiry_date", "binet", "term", "state", "granted_amount", "requested_amount"));
        $wave_state = wave_state($wave["state"]);
        ?>
        <li class="content-line-panel-small">
          <?php
          ob_start();
          ?>
          <i class="fa fa-3x fa-money"></i>
          <span class="name"><?php echo pretty_wave($wave["id"], false); ?></span>
          <span class="state <?php echo $wave_state["color"]; ?>-background">
            <?php echo $wave_state["name"]; ?>
          </span>
          <span class="dates">
            <span class="top green-background">
              <?php
              echo pretty_date($wave["submission_date"]);
              ?>
            </span>
            <span class="bottom orange-background">
              <?php
              echo pretty_date($wave["expiry_date"]);
              ?>
            </span>
          </span>
          <span class="amount green-background">
            <?php
            if (in_array($wave["state"], array("submission", "deliberation"))) {
              $amount = "requested_amount";
            } else {
              $amount = "granted_amount";
            }
            echo pretty_amount($wave[$amount]);
            ?>
          </span>
          <?php
          echo link_to(path("show", "wave", $wave["id"],binet_prefix($wave["binet"],$wave["term"])), "<div>".ob_get_clean()."</div>\n", array("class" => "shadowed clickable-main", "goto" => true));
          ?>
        </li>
        <?php
      }
      ?>
    </ul>
  </div>
</div>
