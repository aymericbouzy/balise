<?php

  $budget_view = true;
  $table_title = "Résumé de la trésorerie du binet";
  ob_start();
?>
 <!-- TODO : DRY it up -->
  <thead>
    <tr>
      <th colspan=2 >Budget</th>
      <th colspan=2 >Montant</th>
      <th colspan=3 >Subventions</th>
    </tr>
    <tr>
      <th>Nom</th>
      <th>Mot clés</th>
      <th>Prévisionnel</th>
      <th>Réel</th>
      <th>Attendues</th>
      <th>Accordées</th>
      <th>Utilisées</th>
    </tr>
  </thead>
  <thead class="separator">
    <tr>
      <td colspan="7">Dépenses</td>
    </tr>
  </thead>
  <tbody class="list">
    <?php
      foreach ($budgets as $budget) {
        if ($budget["amount"] < 0) {
          ?>
          <tr>
            <td class="element_name"><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($binet, $term)), $budget["label"]); ?></td>
            <td class="tags"><?php echo pretty_tags(select_tags_budget($budget["id"]), true); ?></td>
            <td><?php echo pretty_amount($budget["amount"]); ?></td>
            <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_granted"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
          </tr>
        <?php
        }
      }
    ?>
    <tr class="total">
      <td colspan="2">Total des dépenses</td>
      <td><?php echo pretty_amount(sum_array($budgets, "amount", "negative")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets, "real_amount", "negative")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets,"subsidized_amount","negative")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_granted", "negative")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_used", "negative")); ?></td>
    </tr>
  </tbody>
  <thead class="separator">
      <tr>
          <td colspan="7">Recettes</td>
      </tr>
  </thead>
  <tbody class="list">
    <?php
      foreach ($budgets as $budget) {
        if ($budget["amount"] > 0) {
          ?>
          <tr>
            <td class="element_name"><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($binet, $term)), $budget["label"]); ?></td>
            <td class="tags"><?php echo pretty_tags(select_tags_budget($budget["id"]), true); ?></td>
            <td><?php echo pretty_amount($budget["amount"]); ?></td>
            <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_granted"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
          </tr>
        <?php
        }
      }
    ?>
    <tr class="total">
      <td colspan="2">Total des recettes</td>
      <td><?php echo pretty_amount(sum_array($budgets, "amount", "positive")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets, "real_amount", "positive")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets,"subsidized_amount","positive")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_granted", "positive")); ?></td>
      <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_used", "positive")); ?></td>
    </tr>
  </tbody>
  <thead class="separator">
      <tr>
          <td colspan="5"></td>
      </tr>
  </thead>
  <tbody>
      <tr class="total">
          <td colspan="2">Total</td>
          <td><?php echo pretty_amount(sum_array($budgets, "amount")); ?></td>
          <td><?php echo pretty_amount(sum_array($budgets, "real_amount")); ?></td>
          <td><?php echo pretty_amount(sum_array($budgets,"subsidized_amount")); ?></td>
          <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_granted")); ?></td>
          <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_used")); ?></td>
      </tr>
  </tbody>

<?php
  $table = ob_get_clean();

  include VIEW_PATH."binet/finances.php";
