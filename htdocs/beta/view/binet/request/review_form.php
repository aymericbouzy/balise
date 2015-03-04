<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/action/show.css" type="text/css">
<div class="show-container">
  <div class="sh-plus <?php $state_to_color = array("sent" => "orange", "reviewed_accepted" => "green", "reviewed_rejected" => "red"); echo $state_to_color[$request_info["state"]]; ?>-background shadowed">
    <i class="fa fa-fw fa-<?php $state_to_icon = array("sent" => "question", "reviewed_accepted" => "check", "reviewed_rejected" => "times"); echo $state_to_icon[$request_info["state"]]; ?>"></i>
    <div class="text">
      <?php
      switch ($request_info["state"]) {
        case "sent":
        echo "Non traitée";
        break;
        case "reviewed_accepted":
        echo "Acceptée";
        break;
        case "reviewed_rejected":
        echo "Refusée";
        break;
      }
      ?>
    </div>
  </div>
  <div class="sh-actions">
    <?php
      echo button(path("reject", "request", $request_info["id"], binet_prefix($binet, $term), array(), true), "Refuser", "times", "red");
    ?>
  </div>
  <div class="sh-title shadowed">
    <div class="logo">
      <i class="fa fa-5x fa-money"></i>
    </div>
    <div class="text">
      <p class="main">
        <?php echo pretty_binet_term($binet."/".$term); ?>
      </p>
      <p class="sub">
        <?php echo pretty_wave($request_info["wave"]["id"], false); ?>
      </p>
    </div>
  </div>
  <div class="panel light-blue-background shadowed">
    <div class="content">
      <?php echo $current_binet["description"]; ?>
    </div>
  </div>
  <!-- Answer to the wave question -->
  <div class="panel green-background shadowed">
    <div class="content white-text">
      <?php echo $request_info["answer"]; ?>
    </div>
  </div>
  <?php
    ob_start();
    if (has_viewing_rights($current_binet["id"], $current_binet["current_term"])) {
      echo minipane("income", "Recettes", $current_binet["real_income"], $current_binet["expected_income"]);
      echo minipane("spending", "Dépenses", $current_binet["real_spending"], $current_binet["expected_spending"]);
      echo minipane("balance", "Equilibre", $current_binet["real_balance"], $current_binet["expected_balance"]);
      echo "<span class=\"message\"><i class=\"fa fa-fw fa-eye\"></i> Voir l'activité du binet </span>";
      $suffix = "";
    } else {
      $suffix = "_std";
    }
    echo minipane("subsidies_granted".$suffix, "Subventions accordées", $current_binet["subsidized_amount_granted"], NULL);
    echo minipane("subsidies_used".$suffix, "Subventions utilisées", $current_binet["subsidized_amount_used"], NULL);
    $content = ob_get_clean();
    echo link_to(path("",binet_prefix($current_binet["id"], $current_binet["current_term"])),
      "<div>".$content."</div>",
      array("class" => "light-blue-background shadowed sh-bin-stats".clean_string($suffix),"id" => "current-term", "goto" => true));

    if (!is_empty($previous_binet)) {
      ob_start();
      if (has_viewing_rights($current_binet["id"], $current_binet["current_term"] - 1)) {
        echo minipane("income", "Recettes", $previous_binet["real_income"], $previous_binet["expected_income"]);
        echo minipane("spending", "Dépenses", $previous_binet["real_spending"], $previous_binet["expected_spending"]);
        echo minipane("balance", "Equilibre", $current_binet["real_balance"], $previous_binet["expected_balance"]);
        echo "<span class=\"message\"><i class=\"fa fa-fw fa-eye\"></i> Voir l'activité du binet de la promotion précédente </span>";
        $suffix = "";
      } else {
        $suffix = "_std";
      }
      echo minipane("subsidies_granted".$suffix, "Subventions accordées", $previous_binet["subsidized_amount_granted"], NULL);
      echo minipane("subsidies_used".$suffix, "Subventions utilisées", $previous_binet["subsidized_amount_used"], NULL);
      $content = ob_get_clean();
      echo link_to(path("",binet_prefix($current_binet["id"], $current_binet["current_term"] - 1)),
          "<div>".$content."</div>",
          array("class" => "light-blue-background shadowed sh-bin-stats".clean_string($suffix),"id" => "previous-term", "goto" => true));
    }
    ?>
    <div class="panel light-blue-background shadowed">
      <div class="title">
        Subventions <?php echo pretty_binet($request_info["wave"]["binet"],false); ?>
      </div>
      <div class="content" id="wave-owner-subsidy">
        <?php
          echo minipane("granted", "Utilisées / accordées cette année ", $existing_subsidies["used_amount"], $existing_subsidies["requested_amount"]);
          echo minipane("used", "Utilisées / accordées l'année dernière", $previous_subsidies["used_amount"], $previous_subsidies["requested_amount"]);
        ?>
      </div>
    </div>

    <!-- Form -->
    <?php
    foreach (select_subsidies(array("request" => $request_info["id"])) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "budget", "requested_amount", "purpose"));
      $budget = select_budget($subsidy["budget"], array("id", "label", "binet", "term", "real_amount", "amount", "subsidized_amount", "subsidized_amount_granted", "subsidized_amount_used"));
      ?>
      <div class="panel light-blue-background shadowed">
        <?php
          echo link_to(
            path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),
            "<div class=\"title\">".$budget["label"]."<span><i class=\"fa fa-fw fa-eye\"></i>  Voir le budget</span></div>",
            array("goto"=>true)
          );
        ?>
        <div class="content">
          <div class="infos table table-responsive">
            <table>
              <thead>
                <tr>
                  <td class="minititle" >Montant demandé</td>
                  <td class="minititle" >Résumé du budget</td>
                  <td class="minititle" >Subventions</td>
                </tr>
              </thead>
              <tbody>
                <tr class="summary">
                  <td rowspan="3" class="amount-requested"><?php echo pretty_amount($subsidy["requested_amount"],false,true); ?></td>
                  <td> Prévisionnel : <?php echo pretty_amount($budget["amount"])?></td>
                  <td> Attendues : <?php echo pretty_amount($budget["subsidized_amount"])?></td>
                </tr>
                <tr class="summary">
                  <td> Réel : <?php echo pretty_amount($budget["real_amount"])?></td>
                  <td> Reçues : <?php echo pretty_amount($budget["subsidized_amount_granted"])?></td>
                </tr>
                <tr class="summary">
                  <td>Utilisées : <?php echo pretty_amount($budget["subsidized_amount_used"])?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="granted-amount">
            <?php echo form_input("Montant accordé :", "amount_".$subsidy["id"], $form); ?>
          </div>
          <div class="purpose green-background white-text">
            <?php echo $subsidy["purpose"]?>
          </div>
          <div class="explanation">
            <?php echo form_input("Explication :", "explanation_".$subsidy["id"], $form); ?>
          </div>
          <?php
          $html_content = "<div> Voir les commentaires  <i class=\"fa fa-fx fa-chevron-down\"></i></div>";
          echo make_collapse_control($html_content, "subsidyComment".$subsidy["id"]);
          ?>
          <div class="collapse" id="<?php echo "subsidyComment".$subsidy["id"];?>">
            <div class="comments">
              <div class="comment">
                <span class="display-author"> <i class="fa fa-fw fa-user"></i> <span class="name">Hubert Védrine</span> </span>
                <span class="content">Hello world !</span>
                <span class="date">12/05/2015 à 13h07</span>
              </div>
              <div class="comment">
                <span class="display-author"> <i class="fa fa-fw fa-user"></i> <span class="name">Hubert Védrine</span></span>
                <span class="content">Hello world !</span>
                <span class="date">12/05/2015 à 13h07</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }
  ?>
  <div class="submit-button">
    <?php echo form_submit_button("Enregistrer"); ?>
  </div>
</div>
