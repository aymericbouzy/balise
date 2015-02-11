<div class="show-container">
  <?php echo button(path("","binet"),"Retour à l'index binet","chevron-left","blue",true,"","left"); ?>
  <?php
    if (has_viewing_rights($binet["id"], $binet["current_term"])) {
      ?>
      <div class="sh-plus <?php echo $binet["state"]; ?>-background opanel">
        <i class="fa fa-fw fa-<?php $color_to_icon = array("green" => "check", "orange" => "warning", "red" => "minus-circle", "grey" => "moon-o"); echo $color_to_icon[$binet["state"]]; ?>"></i>
        <span class="text">Etat du binet</span>
      </div>
      <?php
    }
  ?>
  <div class="sh-actions">
    <?php
      echo button(contact_binet_path($binet["id"]), "Contacter", "paper-plane", "grey");
      if (has_editing_rights($binet["id"], $binet["current_term"])) {
        echo button(path("edit", "binet", $binet["id"]), "Modifier le binet", "edit", "orange");
      }
      if (is_current_kessier()) {
        if ($binet["subsidy_provider"] == 0) {
          echo button(path("set_subsidy_provider", "binet", $binet["id"], "", array(), true), "Ajouter les droits de subventionneur", "money", "blue");
        }
        echo button(path("change_term", "binet", $binet["id"]), is_empty($binet["current_term"]) ? "Réactiver le binet" : "Faire la passation", "forward", "green");
      }
    ?>
  </div>
  <div class="sh-title opanel">
    <div class="logo">
      <i class="fa fa-5x fa-group"></i>
    </div>
    <div class="text">
      <span class="main">
        <?php
          echo pretty_binet($binet["id"], false);
          if (has_viewing_rights($binet["id"], $binet["current_term"]) && $binet["state"] != "grey") {
            echo link_to(
              path("", "binet", binet_term_id($binet["id"], $binet["current_term"])),
              "<i class=\"fa fa-fw fa-eye\"></i><span> Voir l'activité du binet </span>",
              array("class" => "sh-bin-eye opanel0")
            );
          }
        ?>
      </span>
      <!-- Modal to choose a different term -->
      <?php echo modal_toggle("choose-term", (is_empty($binet["current_term"]) ? "Actuellement inactif" : $binet["current_term"])."<i class=\"fa fa-fw fa-caret-square-o-down\"></i>","sub opanel0","terms"); ?>
    </div>
  </div>
  <?php
  if (!is_empty($binet["current_term"])) {
    ?>
    <div class="sh-bin-admins opanel">
      <span class="title">
        Administrateurs
      </span>
      <?php
      $admins = select_current_admins($binet["id"]);
      if (!empty($admins)) {
        foreach ($admins as $admin) {
          ?>
          <span class="admin">
            <i class="fa fa-fw fa-user logo"></i>
            <i class="fa fa-fw fa-send logo"></i>
            <?php echo pretty_student($admin["id"]); ?>
          </span>
          <?php
        }
      } else {
        ?>
        <i style="padding:5px;color:#DD2C00;" class="fa fa-fw fa-warning"></i> Il n'y a aucun administrateur pour ce binet !
        <?php
      }
      if (is_current_kessier()) {
        ?>
        <div class="add">
          <?php
          echo button(path("new", "admin", "", binet_prefix($binet["id"], $binet["current_term"])), "Ajouter un administrateur", "plus", "green", true, "small");
          if (!empty($admins)){
            echo button(path("index", "admin", "", binet_prefix($binet["id"], $binet["current_term"])), "Supprimer un administrateur", "minus", "red", true, "small");
          }
          ?>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }
  ?>
  <div class="sh-block-normal opanel">
    <?php echo $binet["description"]; ?>
  </div>
  <?php
    if ($binet["state"] != "grey") {
      ob_start();
      if (has_viewing_rights($binet["id"], $binet["current_term"])) {
        echo minipane("income", "Recettes", $binet["real_income"], $binet["expected_income"]);
        echo minipane("spending", "Dépenses", $binet["real_spending"], $binet["expected_spending"]);
        echo minipane("balance", "Equilibre", $binet["real_balance"], $binet["expected_balance"]);
        $suffix = "";
      } else {
        $suffix = "_std";
      }
      echo minipane("subsidies_granted".$suffix, "Subventions accordées", $binet["subsidized_amount_granted"], NULL);
      echo minipane("subsidies_used".$suffix, "Subventions utilisées", $binet["subsidized_amount_used"], NULL);
      $content = ob_get_clean();
      ?>
      <div class="sh-bin-stats<?php echo clean_string($suffix); ?> light-blue-background opanel">
        <?php echo $content; ?>
      </div>
      <?php
    }
    if (!empty($waves)) {
      ?>
      <div class="sh-bin-resume light-blue-background opanel">
        <div class="title">
          Vagues de subventions
        </div>
        <?php
          foreach($waves as $wave) {
            ?>
            <div class="line">
              <span class="label"><?php echo pretty_wave($wave["id"]); ?></span>
              <span class="submission date"><?php echo $wave["submission_date"]; ?></span>
              <span class="expiry date"> <?php echo $wave["expiry_date"]; ?></span>
            </div>
            <?php
          }
        ?>
      </div>
      <?php
    }
  ?>
</div>
<? echo modal("terms","Voir l'activité d'un autre mandat",pretty_terms_list($binet["id"]));?>
