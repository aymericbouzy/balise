<div class="content">
  <div class="form-well" id="transfer-form-header">
    <span class="transfer-form-checkbox"> Nom du budget </span>
    <span class ="transfer-form-amount"> Budget réel / prévisionnel </span>
    <span class = "transfer-form-tags"> Mot clés </span>
  </div>
  <?php
  foreach ($budgets as $budget) {
    ?>
    <div class="form-well">
    <?php
    $budget = select_budget($budget["id"], array("label", "id","real_amount","amount"));
    echo "<span class=\"transfer-form-checkbox\">".form_input($budget["label"], "budget_".$budget["id"], $form)."</span>";
    echo "<span class=\"transfer-form-amount smallfont\">".pretty_amount($budget["real_amount"],true,true).
    " / ".pretty_amount($budget["amount"],true,true)."</span>";
    echo "<span class=\"transfer-form-tags\">".pretty_tags(select_tags_budget($budget["id"]))."</span>";
    ?>
    </div>
    <?php
  }
  ?>
</div>
<?php echo form_submit_button("Transférer les budgets"); ?>
