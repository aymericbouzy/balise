<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/action/show.css" type="text/css">
<div class="show-container">
  <div class="sh-plus <?php $state_to_color = array("sent" => "orange", "reviewed" => "blue", "accepted" => "green", "rejected" => "red"); echo $state_to_color[$request_info["state"]]; ?>-background opanel">
    <i class="fa fa-fw fa-<?php $state_to_icon = array("rough_draft" => "question", "sent" => "question", "reviewed" => "question", "accepted" => "check", "rejected" => "times"); echo $state_to_icon[$request_info["state"]]; ?>"></i>
    <div class="text">
      <?php
      switch ($request_info["state"]) {
        case "rough_draft":
        echo "Brouillon";
        break;
        case "accepted":
        echo "Acceptée";
        break;
        case "rejected":
        echo "Refusée";
        break;
        default:
        if (in_array($request_info["state"], array("sent", "reviewed"))) {
          echo "Envoyée";
        }
      }
      ?>
    </div>
  </div>
  <div class="sh-actions">
    <?php
    if (has_editing_rights($binet, $term)) {
      switch ($request_info["state"]) {
        case "rough_draft":
        echo button(path("edit", "request", $request_info["id"], binet_prefix($binet, $term)), "Modifier", "edit", "grey");
        echo button(path("send", "request", $request_info["id"], binet_prefix($binet, $term), array(), true), "Soumettre", "paper-plane", "green");
        break;
      }
    }
    if (status_admin_binet($request_info["wave"]["binet"], $request_info["wave"]["term"])) {
      echo button(path("", "request", $request_info["id"], binet_prefix($binet, $term)), "Refuser", "times", "red");
    }
    ?>
  </div>
  <div class="sh-title opanel">
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
  <div class="panel light-blue-background opanel">
    <div class="content">
      <?php echo $current_binet["description"]; ?>
    </div>
  </div>
  <!-- Answer to the wave question -->
  <div class="panel green-background opanel">
    <div class="content white-text">
      <?php echo $request_info["answer"]; ?>
    </div>
  </div>
  <?php
  if (has_viewing_rights($binet, $term)) {
    ?>
    <div class="panel light-blue-background opanel" id="current-term">
      <?php
      echo minipane("income", "Recettes", $current_binet["real_income"], $current_binet["expected_income"]);
      echo minipane("spending", "Dépenses", $current_binet["real_spending"], $current_binet["expected_spending"]);
      echo minipane("balance", "Equilibre", $current_binet["real_balance"], $current_binet["expected_balance"]);
      $subsidies_granted_id = "subsidies_granted";
      $subsidies_used_id = "subsidies_used";
    } else {
      echo "<div class=\"sh-bin-stats-std light-blue-background opanel\">";
        $subsidies_granted_id = "subsidies_granted_std";
        $subsidies_used_id = "subsidies_used_std";
      }
      echo minipane($subsidies_granted_id, "Subventions accordées", $current_binet["subsidized_amount_granted"], NULL);
      echo minipane($subsidies_used_id, "Subventions utilisées", $current_binet["subsidized_amount_used"], NULL);
      ?>
    </div>
  <?php
    if($previous_binet){
      if (has_viewing_rights($binet, $term)) {
      ?>
      <div class="panel light-blue-background opanel" id="previous-term">
        <div class="info-right"> Mandat précédent </div>
        <?php
        echo minipane("income", "Recettes", $previous_binet["real_income"], $previous_binet["expected_income"]);
        echo minipane("spending", "Dépenses", $previous_binet["real_spending"], $previous_binet["expected_spending"]);
        echo minipane("balance", "Equilibre", $previous_binet["real_balance"], $previous_binet["expected_balance"]);
        $subsidies_granted_id = "subsidies_granted";
        $subsidies_used_id = "subsidies_used";
        } else {
        echo "<div class=\"sh-bin-stats-std light-blue-background opanel\">";
        $subsidies_granted_id = "subsidies_granted_std";
        $subsidies_used_id = "subsidies_used_std";
      }
      echo minipane($subsidies_granted_id, "Subventions accordées", $previous_binet["subsidized_amount_granted"], NULL);
      echo minipane($subsidies_used_id, "Subventions utilisées", $previous_binet["subsidized_amount_used"], NULL);
      ?>
      </div>
    <?php
    }
    ?>
    <div class="panel light-blue-background opanel" id="wave-owner-subsidy">
      <div class="title">
        Subventions <?php echo pretty_binet($request_info["wave"]["binet"],false); ?>
      </div>
      <div class="content">
        <?php
          echo minipane("granted", "Utilisées / accordées cette année ", $existing_subsidies["used_amount"], $existing_subsidies["requested_amount"]);
          echo minipane("used", "Utilisées / accordées l'année dernière", $previous_subsidies["used_amount"], $previous_subsidies["requested_amount"]);
        ?>
      </div>
    </div>

    <!-- Form -->
    <form role="form" id="request" action="/<?php echo path("grant", "request", $request["id"], binet_prefix($binet, $term)); ?>" method="post">
    <?php
    foreach (select_subsidies(array("request" => $request_info["id"])) as $subsidy) {
      $subsidy = select_subsidy($subsidy["id"], array("id", "budget", "requested_amount", "purpose"));
      $budget = select_budget($subsidy["budget"], array("id", "label", "binet", "term","real_amount","amount","subsidized_amount_granted","subsidized_amount_used"));
      path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])) ?>
      <div class="panel light-blue-background opanel">
        <?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),
                        "<div class=\"title\">".$budget["label"]."<span><i class=\"fa fa-fw fa-eye\"></i>  Voir le budget</span></div>",
                        array("goto"=>true));?>
        <div class="content">
          <div class="infos">
            <p class="amount-requested">
              <span class="minititle">Montant demandé</span>
              <?php echo pretty_amount($subsidy["requested_amount"],false,true); ?></i>
            </p>
            <p class="budget-summary">
              <span class="minititle">Résumé du budget</span>
              <span>Prévisionnel : <?php echo pretty_amount($budget["amount"])?></span>
              <span> Réel : <?php echo pretty_amount($budget["real_amount"])?></span>
            </p>
            <p class="budget-summary">
              <span class="minititle">Subventions</span>
              <span>Reçues : <?php echo pretty_amount($budget["subsidized_amount_granted"])?></span>
              <span>Utilisées : <?php echo pretty_amount($budget["subsidized_amount_used"])?></span>
            </p>
          </div>
          <div class="granted-amount">
          <?php echo form_group_text("Montant accordé :", adds_amount_prefix($subsidy), $request, "request"); ?>
          </div>
          <div class="purpose green-background white-text">
            <?php echo $subsidy["purpose"]?>
          </div>
          <div class="explanation">
            <?php echo form_group_textarea("Explication :", adds_explanation_prefix($subsidy), $request, "request"); ?>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
    <?php echo form_csrf_token(); ?>
    <div class="submit-button">
      <?php echo form_submit_button("Enregistrer"); ?>
    </div>
    </form>
  </div>
