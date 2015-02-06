<div class="show-container">
  <?php echo button(path("","binet"),"Retour à l'index binet","chevron-left","blue",true,"","left"); ?>
  <?php
    if (has_viewing_rights($binet["id"], $binet["current_term"])) {
      ?>
      <div class="sh-plus <?php echo $binet["state"]; ?>-background opanel">
        <i class="fa fa-fw fa-<?php echo array("green" => "check", "orange" => "warning", "red" => "minus-circle")[$binet["state"]]; ?>"></i>
        <span class="text">Etat du binet</span>
      </div>
      <?php
    }
  ?>
  <div class="sh-actions">
    <?php
      echo button(contact_binet_path($binet["id"]), "Contacter", "paper-plane", "grey");
      if (is_current_kessier()) {
       echo button(path("edit", "binet", $binet["id"]), "Modifier le binet", "edit", "orange");
       if($binet["subsidy_provider"]>0){
        $change= false;
        $message="Retirer les droits de subventionneur";
        $color="orange";
       } else {
        $change= true;
        $message= "Ajouter les droits de subventionneur";
        $color="blue";
       }
       echo button(path("set_subsidy_provider", "binet", $binet["id"], "", array(), $change),$message,"money","blue");
       echo button(path("change_term", "binet", $binet["id"]),"Faire la passation","forward","green");
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
          echo pretty_binet_no_link($binet["id"]);
          if (has_viewing_rights($binet["id"], $binet["current_term"])) {
            echo link_to(
              path("", "binet", binet_term_id($binet["id"], $binet["current_term"])),
              "<i class=\"fa fa-fw fa-eye\"></i><span> Voir l'activité du binet </span>",
              array("class" => "sh-bin-eye opanel0")
            );
          }
        ?>
      </span>
      <!-- Modal to choose a different term -->
      <span class="sub opanel0" id="choose-term" data-toggle="modal" data-target="#terms">
        <?php echo $binet["current_term"]; ?><i class="fa fa-fw fa-caret-square-o-down"></i>
      </span>
      <div class="balise-modal fade" id="terms" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-content balise-modal-container">
            <div class="modal-body">
              <?php echo close_button("modal"); ?>
              <span class="header">Voir l'activité d'un autre mandat</span>
              <div class="content">
                <?php echo pretty_terms_list($binet["id"]); ?>
              </div>
            </div>
          </div>
      </div>
      <!-- Modal -->
    </div>
  </div>
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
          <?php echo button(path("new", "admin", "", binet_prefix($binet["id"], $binet["current_term"])), "Ajouter un administrateur", "plus", "green", true, "small"); ?>
          <?php echo button(path("index", "admin", "", binet_prefix($binet["id"], $binet["current_term"])), "Supprimer un administrateur", "minus", "red", true, "small"); ?>
        </div>
        <?php
      }
    ?>
  </div>
  <div class="sh-block-normal opanel">
    <?php echo $binet["description"]; ?>
  </div>
  <?php
    if (has_viewing_rights($binet["id"], $binet["current_term"])) {
      ?>
      <div class="sh-bin-stats light-blue-background opanel">
        <?php
          echo minipane("income", "Recettes", $binet["real_income"], $binet["expected_income"]);
          echo minipane("spending", "Dépenses", $binet["real_spending"], $binet["expected_spending"]);
          echo minipane("balance", "Equilibre", $binet["real_balance"], $binet["expected_balance"]);
          $subsidies_granted_id= "subsidies_granted";
          $subsidies_used_id= "subsidies_used";
        } else {
          echo "<div class=\"sh-bin-stats-std light-blue-background opanel\">";
          $subsidies_granted_id= "subsidies_granted_std";
          $subsidies_used_id= "subsidies_used_std";
        }
        echo minipane($subsidies_granted_id, "Subventions accordées", $binet["subsidized_amount_granted"], NULL);
        echo minipane($subsidies_used_id, "Subventions utilisées", $binet["subsidized_amount_used"], NULL);
        ?>
      </div>
      <?php
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
