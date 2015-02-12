<form role="form" id="request" action="/<?php echo path($form_action, "request", $form_action == "create" ? "" : $request["id"], binet_prefix($binet, $term)); ?>" method="post">
  <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>css/action/show.css" type="text/css">
  <div class="panel green-background opanel">
    <div class="content white-text">
      <?php echo form_group_text($request["wave"]["question"], "answer", $request, "request",array("placeholder" => "Justifiez votre demande","style"=>"color:#fff")); ?>
    </div>
  </div>
  <?php
  foreach ($budgets_involved as $budget) {
    $budget = select_budget($budget["id"], array("id", "label", "binet", "term","real_amount","amount","subsidized_amount_granted","subsidized_amount_used"));
    path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])) ?>
    <div class="panel light-blue-background opanel">
      <?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),
        "<div class=\"title\">".$budget["label"]."<span><i class=\"fa fa-fw fa-eye\"></i>  Voir le budget</span></div>",
        array("goto"=>true));?>
      <div class="content">
        <div class="edit-infos">
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
        <div class="requested-amount">
          <?php echo form_group_text("", adds_amount_prefix($budget), $request, "request",array("placeholder" => "Montant demandé pour ce budget" )); ?>
        </div>
        <div class="explanation">
          <?php echo form_group_textarea("", adds_purpose_prefix($budget), $request, "request",array("placeholder" => "Explication pour ce budget" )); ?>
        </div>
      </div>
    </div>
    <?php
  }
  ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_hidden("wave", $request["wave"]["id"]); ?>
  <div class="submit-button">
    <?php echo form_submit_button($submit_label); ?>
  </div>
</form>
