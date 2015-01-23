<script src = "<?php echo ASSET_PATH; ?>js/show-budget.js"></script>
<div class="show-container">
  <!-- TODO : green recette, red dépense -->
  <div class="sh-plus green-background opanel">
    <!-- TODO : fa-plus ou fa-minus selon le signe du budget -->
    <i class="fa fa-fw fa-plus-circle"></i>
    <div class="text"> <!-- TODO Recette ou dépense --> </div>
  </div>
  <div class="sh-title opanel">
    <div class="logo">
      <i class="fa fa-5x <?php echo $budget["amount"] > 0 ? "fa-plus-circle" : "fa-minus-circle"; ?>"></i>
    </div>
    <div class="text">
      <p class="main">
        <?php echo $budget["label"]; ?>
      </p>
      <p class="sub">
        <?php echo pretty_binet_term($budget["binet"]."/".$budget["term"]); ?>
      </p>
    </div>
  </div>
  <div class="sh-bu-ratio opanel">
    <div class="header">
      Budget réel / prévisionnel
    </div>
    <div>
      <div class="used" id="real_budget">
        <?php echo ratio_bar($budget["real_amount"], $budget["amount"]); ?>
      </div>
    </div>
  </div>
  <!-- TODO : seulement si cela a du sens !-->
  <div class="sh-bu-ratio opanel">
    <div class="header">
      Subventions utilisées / accordées
    </div>
    <div>
      <div class="used" id="subsidies">
        <?php echo ratio_bar($budget["subsidized_amount_used"], $budget["subsidized_amount_granted"]); ?>
      </div>
    </div>
  </div>
  <div class="sh-bu-tags opanel">
    <?php echo pretty_tags(select_tags_budget($budget["id"])); ?>
  </div>
  <div class="sh-bu-operations opanel">
    <div class="pieID pie">
    </div>
    <ul class="pieID legend">
      <li>
        <!--TODO : pour chaque opération , ajouter : -->
        <em>Nom del'opération</em>
        <span>Montant de l'opération</span>
        <!-- ------------- -- >
      </li>
    </ul>
  </div>
</div>
