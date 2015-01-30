<div id="public-index-wrapper">
  <div id="action-header" class="opanel2">
    <div id="action-title">Binets</div>
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

    <li class="content-line-panel">
      <?php
        foreach ($binets as $binet) {
          $binet = select_binet($binet["id"], array("id", "name", "current_term"));
          if (is_current_kessier()) {
            $binet["balance"] = select_term($binet["id"]."/".$binet["current_term"], array("balance"));
            $binet["state_color"] = $binet["balance"] > 0 ? "green" : "red";
          }
          ob_start();
          ?>
          <div><i class="fa fa-3x fa-group"></i>
            <span class="name"><?php echo $binet["name"]; ?></span>
            <?php
              if (is_current_kessier()) {
                ?>
                <span class"state <?php echo $binet["state_color"]; ?>-background">Etat du binet</span>
                <?php
              }
            ?>
            <span class="users">
              <?php
                foreach (select_current_admins($binet["id"]) as $admin) {
                  ?>
                  <span class="prez"><?php echo pretty_student($admin); ?></span>
                  <?php
                }
              ?>
            </span>
            <?php
              if (is_current_kessier()) {
                ?>
                <span class"amount <?php echo $binet["state_color"]; ?>-background"><?php echo $binet["balance"]; ?></span>
                <?php
              }
            ?>
          </div>
          <?php

          echo link_to(path("show", "binet", $binet["id"]), ob_get_clean(), array("class"=>"opanel clickable-main","goto"=>true));
        }
      ?>
      <span class="actions">
        <?php
        echo button(contact_binet_path($binet["id"]), "Contacter", "paper-plane", "grey");
        ?>
      </span>
    </li>
    <?php } ?>
  </ul>
</div>
<?php echo fuzzy_load_scripts("public-index-wrapper","name"); ?>
