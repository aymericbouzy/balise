<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/action/show.css" type="text/css">
<?php $request_state = request_state($request_info["state"],has_editing_rights($request_info["wave"]["binet"], $request_info["wave"]["term"])); ?>
<div class="sh-plus <?php echo $request_state["color"]; ?>-background shadowed">
  <i class="fa fa-fw fa-<?php echo $request_state["icon"] ?>"></i>
  <div class="text">
    <?php echo $request_state["name"]; ?>
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
    <?php echo insert_tooltip("<span>".pretty_date($request["sending_date"])."</span>","Date de réception de la requête");?>
  </div>
  <div class="text">
    <p class="main">
      <?php echo pretty_binet_term($binet."/".$term); ?>
    </p>
    <p class="sub">
      <?php echo pretty_wave($request_info["wave"]["id"]); ?>
    </p>
  </div>
</div>
<div class="panel light-blue-background shadowed">
  <div class="content">
    <?php echo (is_empty($current_binet["description"]) ? "Aucune description pour ce binet" : $current_binet["description"]); ?>
  </div>
</div>
<!-- Answer to the wave question -->
<div class="panel green-background shadowed">
  <div class="content white-text">
    <?php echo $request_info["answer"]; ?>
  </div>
</div>
<!-- Form -->
<?php
foreach (select_subsidies(array("request" => $request_info["id"])) as $subsidy) {
  $subsidy = select_subsidy($subsidy["id"], array("id", "budget", "requested_amount", "purpose"));
  $budget = select_budget($subsidy["budget"], array("id", "label", "binet", "term", "real_amount", "amount", "subsidized_amount", "subsidized_amount_granted", "subsidized_amount_used", "subsidized_amount_available"));
  if($subsidy["conditional"] == 1){
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
                <td> Disponibles : <?php echo pretty_amount($budget["subsidized_amount_available"])?></td>
              </tr>
              <tr class="summary">
              	<td></td>
                <td>Utilisées : <?php echo pretty_amount($budget["subsidized_amount_used"])?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="granted-amount">
          <?php
            echo pretty_amount($subsidy["granted_amount"]);
            echo form_input("Montant accordé après vérification des conditions :", "amount_".$subsidy["id"], $form);
          ?>
        </div>
        <div class="purpose green-background white-text">
          <?php echo $subsidy["purpose"]?>
        </div>
        <div class="explanation">
          <?php echo $susbsidy["explanation"]; ?>
        </div>
      </div>
    </div>
  <?php
  }
}
?>
<div class="submit-button">
  <?php echo form_submit_button("Enregistrer"); ?>
</div>
