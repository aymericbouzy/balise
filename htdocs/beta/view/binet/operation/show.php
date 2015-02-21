<script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
<?php echo initialize_popovers(); ?>
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
          case "accepted";
          echo button(path("review", "operation", $operation["id"], binet_prefix($binet, $term)), "Modifier la répartition sur les budgets", "bar-chart", "teal");
          break;
        }
        echo button(path("edit", "operation", $operation["id"], binet_prefix($binet, $term)), "Modifier", "edit", "grey");
        echo button(path("delete", "operation", $operation["id"], binet_prefix($binet, $term), array(), true), "Supprimer", "trash", "red");
      }
      if (has_editing_rights_for_suggested_operation($operation["id"]) && !has_editing_rights($binet, $term)) {
        echo button(path("edit", "operation", $operation["id"]), "Modifier", "edit", "grey");
      }
      if (is_current_kessier()) {
        switch ($operation["state"]) {
          case "waiting_validation":
          echo button(path("validate", "operation", $operation["id"], "", array(), true), "Valider", "check", "green");
          echo button(path("reject", "operation", $operation["id"], "", array(), true), "Refuser", "times", "red");
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
  <div class="panel transparent-background" id="sh-op-amount-refs">
    <div class="panel-split-left opanel" id="sh-op-amount">
      <?php echo pretty_amount($operation["amount"]); ?> <i class="fa fa-euro"></i>
    </div>
    <div class="panel-split-right opanel" id="sh-op-refs">
      <i class="fa fa-fw fa-folder-o"></i>
      <?php echo $operation["bill"] ?: "Aucune facture associée"; ?><br>
      <span class="side-information"><?php echo pretty_date($operation["bill_date"]); ?></span></br>
      <?php echo pretty_operation_type($operation["type"])." ".($operation["payment_ref"] ?: "Aucune référence de paiement associée"); ?><br>
      <span class="side-information"><?php echo pretty_date($operation["payment_date"]);?></span>
    </div>
  </div>
  <div class="panel opanel blue-background">
    <div class="content">
      <?php
        if($operation["comment"] != ""){
          echo "<div class=\"panel-important-element light-blue-background\">".$operation["comment"]."</div>";
        }
      ?>
      <div class="panel-important-element light-blue-background"><i class="fa fa-fw fa-user"></i>
        <?php echo $operation["paid_by"] ? paid_by_to_caption($operation["paid_by"]) : "Aucun payeur enregistré";?>
      </div>
      <div class="panel-important-element light-blue-background">
        <?php echo pretty_tags(select_tags_operation($operation["id"]), true); ?>
      </div>
    </div>
  </div>
  <div class="panel opanel light-blue-background">
    <div class="title-small">
      Utilisation de subventions - <i> Cliquez sur le budget pour avoir l'objectif de la subvention</i>
    </div>
    <div class="content">
      <?php foreach(select_subsidies_and_requests_operation($operation["id"]) as $request => $subsidies){
        echo pretty_wave(select_request($request,array("wave"))["wave"]);
        echo "<div>";
        foreach($subsidies as $subsidy){
          $subsidy = select_subsidy($subsidy,array("budget","purpose"));
          $html_button = "<button class=\"pill\">".pretty_budget($subsidy["budget"],false,false)." </button>";
          $popover_title = "Justification de la demande";
          $popover_content = $subsidy["purpose"];
          echo insert_popover($html_button,$popover_content,$popover_title,"left");
        }
        echo "</div>";
      }?>
    </div>
  </div>
  <div class="panel opanel light-blue-background">
    <div class="title-small">
      Répartition sur les budgets
    </div>
    <div class="content">
      <?php
      $budgets = select_budgets_operation($operation["id"]);
      if (!is_empty($budgets) && sizeOf($budgets) > 1) {
        ?>
        <div class="pieID pie">
        </div>
        <ul class="pieID legend">
          <?php
          foreach ($budgets as $budget) {
            ?>
            <li>
              <em><?php echo pretty_budget($budget["id"], true, false); ?></em>
              <span><?php echo pretty_amount($budget["amount"], false); ?></span>
            </li>
            <?php
          }
          ?>
        </ul>
        <script>createPie(".pieID.legend", ".pieID.pie");</script>
        <?php
      } elseif (!is_empty($budgets)) {
        echo pretty_budget($budgets[0]["id"], true);
      } else {
        ?>
        <i>Vous n'avez aucun budget associé à cette opération ?</i>
        <?php
      }
      ?>
    </div>
  </div>
</div>
