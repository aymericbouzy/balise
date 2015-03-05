<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/action/show.css" type="text/css">
<div class="panel light-blue-background shadowed">
  <div class="content">
    <?php echo pretty_wave($request["wave"]["id"]);?>
  </div>
</div>
<?php
if ($_GET["action"] == "new") {
  $current_term_binet = current_term($binet);
  $checked = $current_term_binet != $term;
  $term_redirect = $current_term_binet + ($checked ? 0 : 1);
  ?>
  <div class="panel light-blue-background shadowed">
    <div class="content">
      Faire la demande pour la promotion :
      <div class="switch" id="requestForm_chooseTerm">
        <span class="left component <?php echo $checked? "inactive":"active";?>" >
          <?php echo link_to(path("new", "request", "", binet_prefix($binet, $term_redirect),array("wave" => $request["wave"]["id"])), $current_term_binet); ?>
          </span>
        <span class="left component <?php echo $checked? "active":"inactive";?>" >
          <?php echo link_to(path("new", "request", "", binet_prefix($binet, $term_redirect),array("wave" => $request["wave"]["id"])), $checked ? $term : $term_redirect ); ?>
        </span>
      </div>
      <input style="visibility:hidden" type="checkbox" <?php echo $checked ? " checked" : "" ?>>
    </div>
  </div>
  <?php
}
?>
<div class="panel green-background shadowed">
  <div class="content white-text" id="description">
    <?php echo form_input($request["wave"]["question"], "answer", $form, array("placeholder" => "Justifiez votre demande", "style" => "color:#fff")); ?>
  </div>
</div>
<?php
foreach (select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array("<", 0))) as $budget) {
  $budget = select_budget($budget["id"], array("id", "label", "binet", "term","real_amount","amount","subsidized_amount_granted","subsidized_amount_used"));
  ?>
  <div class="panel light-blue-background shadowed">
    <?php
    echo link_to(path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),
      "<div class=\"title\">".$budget["label"]."<span><i class=\"fa fa-fw fa-eye\"></i>  Voir le budget</span></div>",
      array("goto"=>true));
    ?>
    <div class="content">
      <div class="edit-infos table-responsive">
        <table>
          <thead>
            <tr>
              <td class="minititle">Résumé du budget</td>
              <td class="minititle">Subventions</td>
            </tr>
          </thead>
          <tbody>
          <tr class="summary">
            <td>Prévisionnel : <?php echo pretty_amount($budget["amount"])?></td>
            <td>Reçues : <?php echo pretty_amount($budget["subsidized_amount_granted"])?></td>
          </tr>
          <tr class="summary">
            <td> Réel : <?php echo pretty_amount($budget["real_amount"])?></td>
            <td>Utilisées : <?php echo pretty_amount($budget["subsidized_amount_used"])?></td>
          </tr>
          <tbody>
        </table>
      </div>
      <div class="requested-amount">
        <?php echo form_input("", "amount_".$budget["id"], $form, array("html_decoration" => array("placeholder" => "Montant demandé pour ce budget"))); ?>
      </div>
      <div class="explanation">
        <?php echo form_input("", "purpose_".$budget["id"], $form, array("html_decoration" => array("placeholder" => "Explication pour ce budget"))); ?>
      </div>
    </div>
  </div>
  <?php
}
?>
<?php echo form_hidden("wave", $request["wave"]["id"]); ?>
<div class="submit-button">
  <?php echo form_submit_button("Sauvegarder"); ?>
</div>
