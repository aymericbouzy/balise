<script src = "<?php echo ASSET_PATH; ?>js/piechart.js"></script>
<div class="show-container">
  <div class="sh-plus <?php echo $budget["amount"] > 0 ? "green" : "red" ?>-background opanel">
    <i class="fa fa-fw fa-<?php echo $budget["amount"] > 0 ? "plus" : "minus" ?>-circle"></i>
    <div class="text"><?php echo $budget["amount"] > 0 ? "Recette" : "Dépense" ?></div>
  </div>
  <div class="sh-actions">
    <?php
    if (has_editing_rights($binet,$term)) {
      echo button(path("edit", "budget", $budget["id"], binet_prefix($binet, $term)), "Modifier", "edit", "grey");
      if (budget_is_alone()) {
        echo button(path("delete", "budget", $budget["id"], binet_prefix($binet, $term), array(), true), "Supprimer", "trash", "red");
      }
    }
    ?>
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
  <?php
    if (!in_array($budget["subsidized_amount_granted"], array("", "0", 0))) {
      ?>
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
      <?php
    }
  ?>
  <div class="sh-bu-tags opanel">
    <?php echo pretty_tags(select_tags_budget($budget["id"])); ?>
  </div>
  <div class="sh-bu-operations opanel">
  <?php
    $operations = select_operations_budget($budget["id"]);
    if (!empty($operations) && sizeOf($operations)>1) {
      ?>
        <div class="pieID pie">
        </div>
        <ul class="pieID legend">
          <li>
            <?php
              foreach ($operations as $operation) {
                ?>
                <em><?php echo pretty_operation($operation["id"]); ?></em>
                <span><?php echo pretty_amount($operation["amount"]); ?></span>
                <?php
              }
            ?>
          </li>
        </ul>
      <?php
    }
    else{
      if(!empty($operations)){
        echo pretty_operation($operations[0]["id"]);
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
