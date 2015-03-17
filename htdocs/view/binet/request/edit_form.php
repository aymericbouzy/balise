<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/action/show.css" type="text/css">
<div class="panel light-blue-background shadowed">
  <div class="content">
    <?php echo pretty_wave($request["wave"]["id"]);?>
  </div>
</div>
<?php
if ($_GET["action"] == "new") {
  $current_term_binet = current_term($binet);
  $current_term_active = $current_term_binet == $term;
  ?>
  <div class="panel light-blue-background shadowed">
    <div class="content">
      Faire la demande pour la promotion :
      <div class="switch shadowed0" id="requestForm_chooseTerm">
        <?php ob_start(); ?>
        <span class="left component <?php echo $current_term_active ? "active" : "inactive"; ?>" >
          <?php echo $current_term_binet." ".($current_term_active ? "<i class=\"fa fa-fw fa-check\"></i>" : ""); ?>
        </span>
        <?php echo link_to(path("new", "request", "", binet_prefix($binet, $current_term_binet), array("wave" => $request["wave"]["id"])), ob_get_clean(), array("goto" => true)); ?>
        <?php ob_start(); ?>
        <span class="left component <?php echo $current_term_active ? "inactive" : "active"; ?>" >
          <?php echo ($current_term_binet + 1)." ".($current_term_active ? " " : "<i class=\"fa fa-fw fa-check\"></i>"); ?>
        </span>
        <?php echo link_to(path("new", "request", "", binet_prefix($binet, $current_term_binet + 1), array("wave" => $request["wave"]["id"])), ob_get_clean(), array("goto" => true)); ?>
        </span>
      </div>
    </div>
  </div>
  <?php
}
?>
<div class="panel grey-background shadowed">
  <div class="content">
    <?php echo $request["wave"]["description"]; ?>
  </div>
</div>
<div class="panel green-background shadowed">
  <div class="content white-text" id="description">
    <?php echo form_input($request["wave"]["question"], "answer", $form, array("html_decoration" => array("placeholder" => "Justifiez votre demande", "style" => "color:#fff"))); ?>
  </div>
</div>
<?php
foreach (select_budgets(array("binet" => $GLOBALS["binet"], "term" => $GLOBALS["term"], "amount" => array("<", 0))) as $budget) {
  $budget = select_budget($budget["id"], array("id", "label", "binet", "term","real_amount","amount","subsidized_amount_granted","subsidized_amount_used", "subsidized_amount_available"));
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
            <td>Disponibles : <?php echo pretty_amount($budget["subsidized_amount_available"])?></td>
          </tr>
          <tr class="summary">
            <td>Réel : <?php echo pretty_amount($budget["real_amount"])?></td>
            <td>Utilisées : <?php echo pretty_amount($budget["subsidized_amount_used"])?></td>
          </tr>
          <tbody>
        </table>
      </div>
      <div class="requested-amount">
        <?php echo form_input("", "amount_".$budget["id"], $form, array("html_decoration" => array("placeholder" => "Montant demandé"))); ?>
      </div>
      <div class="explanation">
        <?php echo form_input("", "purpose_".$budget["id"], $form, array("html_decoration" => array("placeholder" => "Explication"))); ?>
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
