<div class="show-container">
  <div class="sh-plus <?php echo $budget["amount"] > 0 ? "green" : "red" ?>-background opanel">
    <i class="fa fa-fw fa-<?php echo $budget["amount"] > 0 ? "plus" : "minus" ?>-circle"></i>
    <div class="text"><?php echo $operation["amount"] > 0 ? "Recette" : "Dépense" ?></div>
  </div>
  <div class="sh-actions">
    <?php
    if (has_editing_rights()) {
      echo button(path("edit", "budget", $budget["id"], binet_prefix($binet, $term)), "Modifier", "edit", "grey");
      if (budget_is_alone()) {
        echo button(path("delete", "budget", $budget["id"], binet_prefix($binet, $term)), "Supprimer", "trash", "red");
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
    if (!empty($budget["subsidized_amount_granted"])) {
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
  <?php
    $operations = select_operations_budget($budget["id"]);
    if (!empty($operations)) {
      ?>
      <div class="sh-bu-operations opanel">
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
      </div>
      <?php
    }
  ?>
</div>
