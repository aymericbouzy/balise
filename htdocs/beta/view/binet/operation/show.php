<script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
<div class="show-container">
  <div class="sh-plus <?php echo $operation["amount"] > 0 ? "green" : "red" ?>-background opanel">
    <i class="fa fa-fw fa-<?php echo $operation["amount"] > 0 ? "plus" : "minus" ?>-circle"></i>
    <div class="text"><?php echo $operation["amount"] > 0 ? "Recette" : "Dépense" ?></div>
  </div>
  <div class="sh-actions">
    <?php
      if (has_editing_rights($binet, $term)) {
        switch ($operation["state"]) {
          case "suggested":
          echo button(path("review", "operation", $operation["id"], binet_prefix($binet, $term)), "Ajouter", "plus", "green");
          break;
          case "waiting_validation":
          echo button("", "En attente de validation par la Kès", "question", "orange", false);
          break;
          case "validated":
          echo button("", "Validée", "check", "green", false);
          break;
        }
        echo button(path("edit", "operation", $operation["id"], binet_prefix($binet, $term)), "Modifier", "edit", "grey");
        echo button(path("delete", "operation", $operation["id"], binet_prefix($binet, $term)), "Supprimer", "trash", "red");
      }
      if (is_current_kessier()) {
        switch ($operation["state"]) {
          case "waiting_validation":
          echo button(path("validate", "operation", $operation["id"], binet_prefix($binet, $term)), "Valider", "check", "green");
          echo button(path("reject", "operation", $operation["id"], binet_prefix($binet, $term)), "Refuser", "times", "red");
          break;
        }
      }
    ?>
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
  <div class="sh-piechart-panel opanel">
    <div class="pieID pie">
    </div>
    <ul class="pieID legend">
      <li>
        <?php
          foreach ($budgets as $budget) {
            ?>
            <em><?php echo pretty_budget($budget["id"]); ?></em>
            <span><?php echo pretty_amount($budget["amount"]); ?></span>
            <?php
          }
        ?>
        </li>
      </ul>
    <?php
    }
    else{
      if(!empty($budgets)){
        echo pretty_budget($budgets[0]["id"]);
      }
      else{
        ?>
        Vous n'avez aucune opération associée à ce budget !
        <?php
      }
    }
  ?>
  </div>
</div>
