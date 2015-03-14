<div id="public-index-wrapper">
  <div id="action-header" class="shadowed2">
    <div id="action-title">Binets</div>
    <div class="searchbar">
      <?php echo fuzzy_input(); ?>
    </div>
    <div class="alpha-selecter">
      <a href="#deactivated">Voir les binets abandonnés</a>
    </div>
  </div>
  <ul class="list">
    <?php
      foreach ($binets as $binet) {
        ?>
        <li class="content-line-panel">
        <?php
          $binet = select_binet($binet["id"], array("id", "current_term","name"));
          if (is_current_kessier()) {
            $binet = array_merge(select_term_binet($binet["id"]."/".$binet["current_term"], array("real_balance", "state")), $binet);
          }
          ob_start();
          ?>
            <i class="fa fa-fw fa-group"></i>
            <span class="name search_property"><?php echo pretty_binet($binet["id"], false); ?></span>
            <span class="users">
              <?php
                foreach (select_current_admins($binet["id"]) as $admin) {
                  echo "<span class=\"pill\">".pretty_student($admin["id"])."</span>";
                }
              ?>
            </span>
            <span class="post-operation">
              <?php
                $link = link_to(path("new", "operation", "", binet_prefix($binet["id"], $binet["current_term"])), "<i class=\"fa fa-fw fa-plus-circle\"></i>");
                echo insert_tooltip($link,"Poster une opération pour le binet ".$binet["name"]);
              ?>
            </span>
            <?php
              if (is_current_kessier()) {
                ?>
                <span class="amount <?php echo $binet["state"]; ?>-background"><?php echo pretty_amount($binet["real_balance"]); ?></span>
                <?php
              }
          echo link_to(path("show", "binet", $binet["id"]), "<div>".ob_get_clean()."</div>\n", array("class"=>"shadowed clickable-main","goto"=>true));
          ?>
          <span class="actions">
            <?php
            if(has_viewing_rights($binet["id"],$binet["current_term"])){
              echo button(path("",binet_prefix($binet["id"],$binet["current_term"])), "Voir l'activité du binet", "eye", "blue");
            } else {
              echo button(contact_binet_path($binet["id"]), "Contacter", "paper-plane", "grey");
            }
            ?>
          </span>
        </li>
        <?php
      }
    ?>
  </ul>
  <?php
    $html_content ="<div id=\"deactivated\" class=\"panel shadowed\"><div class=\"title\">".
    "  Binets qui n'ont pas trouvé de repreneur <i class=\"fa fa-chevron-down fa-fw\"></i> </div></div>";
    echo make_collapse_control($html_content, "deactivatedBinets_list");
  ?>
  <ul class="list collapse" id="deactivatedBinets_list">
    <?php
      foreach(select_binets(array("current_term" => array("IS", "NULL"))) as $binet){
         ?>
          <li class="content-line-panel">
          <?php
          ob_start();
          ?>
          <i class="fa fa-fw fa-group deactivated-text"></i>
          <span class="name"><?php echo pretty_binet($binet["id"], false); ?></span>
          <?php
          echo link_to(path("show", "binet", $binet["id"]), "<div>".ob_get_clean()."</div>\n", array("class"=>"shadowed clickable-main","goto"=>true));
          ?>
        </li>
        <?php
      }
    ?>
  </ul>
</div>
<?php echo fuzzy_load_scripts("public-index-wrapper","search_property"); ?>
