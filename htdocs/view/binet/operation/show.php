<div class="show-container">
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
</div>
