<div id="wave-index-wrapper">
  <div id="wave-index">
    <ul class="list">
      <?php
      foreach ($waves as $wave) {
        $wave = select_wave($wave["id"], array("id", "name", "submission_date", "expiry_date", "binet", "term", "state", "granted_amount", "requested_amount"));
        ?>
        <li class="content-line-panel-small">
          <?php
          ob_start();
          ?>
          <i class="fa fa-3x fa-money"></i>
          <span class="name"><?php echo pretty_wave($wave["id"], false); ?></span>
          <span class="state <?php $state_to_color = array("submission" => "green", "deliberation" => "orange", "distribution" => "grey", "closed" => "red"); echo $state_to_color[$wave["state"]]; ?>-background">
            <?php $state_to_caption = array("submission" => "Ouverte", "deliberation" => "Dépôt terminé", "distribution" => "En cours", "closed" => "Terminée"); echo $state_to_caption[$wave["state"]]; ?>
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
          echo link_to(path("show", "wave", $wave["id"],binet_prefix($wave["binet"],$wave["term"])), "<div>".ob_get_clean()."</div>\n", array("class" => "opanel clickable-main", "goto" => true));
          ?>
        </li>
        <?php
      }
      ?>
    </ul>
  </div>
</div>
