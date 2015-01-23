<script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
<div class="show-container">
  <div class="sh-plus opanel">
    <!-- TODO : fa-plus ou fa-minus selon le signe de l'opération -->
    <i class="fa fa-fw fa-plus-circle"></i>
    <div class="text"> <!-- TODO Recette ou dépense --> </div>
  </div>
  <div class="sh-title opanel">
    <div class="logo">
      <i class="fa fa-calculator fa-5x"></i>
    </div>
    <div class="text">
      <div class="main">
        <?php echo pretty_binet($operation["binet"]); ?>
      </div>
      <div class="sub">
        Mandat <?php echo $operation["term"]; ?>
      </div>
    </div>
  </div>
  <div class="sh-op-amount opanel">
    <?php echo pretty_amount($operation["amount"]); ?> <i class="fa fa-euro"></i>
  </div>
  <div class="sh-op-refs opanel">
    <i class="fa fa-fw fa-folder-o"></i> </br>
    <?php echo $operation["bill"] ?: "Aucune facture associée"; ?> </br>
    <?php echo pretty_operation_type($operation["type"])." ".($operation["reference"] ?: "Aucune référence de paiement associée"); ?>
  </div>
  <div class="sh-op-info opanel">
    <?php echo $operation["comment"]; ?>
  </div>
  <div class="sh-op-payer opanel">
    <i class="fa fa-fw fa-user"></i> <?php echo $operation["paid_by"] ? pretty_student($operation["paid_by"]) : "Aucun payeur enregistré"; ?>
  </div>
  <div class="sh-op-budgets opanel">
    <div class="pieID pie">
    </div>
    <ul class="pieID legend">
      <li>
        <!--TODO : pour chaque opération , ajouter : -->
        <em>Nom du budget</em>
        <span>Montant utilisé</span>
        <!-- ------------- -- >
      </li>
    </ul>
  </div>
</div>
