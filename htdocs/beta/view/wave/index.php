<div id="public-index-wrapper">
  <div id="action-header" class="opanel2">
    <div id="action-title">Vagues de subventions</div>
    <div class="searchbar">
        <?php echo fuzzy_input(); ?>
    </div>
    <div class="alpha-selecter">
      <?php
      // foreach(range('A', 'Z') as $letter) {
      //   echo link_to("#".$letter, $letter);
      // }
      ?>
    </div>
  </div>
  <ul class="list">
    <?php
      foreach ($waves as $wave) {
        $wave = select_wave($wave["id"], array("id", "name", "submission_date", "expiry_date", "binet", "term", "state", "granted_amount", "requested_amount"));
        ?>
        <li class="content-line-panel">
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

            if (in_array($wave["state"], array("submission", "deliberation"))) {
              ?>
              <span class="actions">
                <?php
                  echo button(path("", ""), "Demander des subventions", "question", "green");
                ?>
              </span>
              <?php
            }
          ?>
        </li>
        <?php
      }
    ?>
  </ul>
</div>
<?php echo fuzzy_load_scripts("public-index-wrapper","name"); ?>
