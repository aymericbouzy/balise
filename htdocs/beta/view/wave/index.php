<div id="public-index-wrapper">
  <div id="action-header" class="shadowed2">
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
        $wave_state = wave_state($wave["state"]);
        ?>
        <li class="content-line-panel">
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
            echo link_to(path("show", "wave", $wave["id"], binet_prefix($wave["binet"],$wave["term"])), "<div>".ob_get_clean()."</div>\n", array("class" => "shadowed clickable-main", "goto" => true));

            if (in_array($wave["state"], array("submission", "deliberation"))) {
              ?>
              <span class="actions">
                <?php
                  echo modal_toggle("ask_for_subsidies".$wave["id"],"<i class=\"fa fa-fw fa-question anim\"></i> <span class=\"olabel\"> Demander des subventions </span>",
                    "round-button green-background shadowed2","choose_binet".$wave["id"]);
                  ob_start();
                  foreach(select_terms(array("student"=>$_SESSION["student"])) as $term_admin) {
                    $term_admin = select_term_binet($term_admin["id"], array("binet","term"));
                    echo link_to(path("new", "request", "", binet_prefix($term_admin["binet"],$term_admin["term"]), array("wave" => $wave["id"])),
                      pretty_binet_term($term_admin["id"], false) ,
                      array("class" => "modal-list-element shadowed0"));
                  }
                  echo modal("choose_binet".$wave["id"],"Choisir un binet pour lequel faire une demande",ob_get_clean());
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
