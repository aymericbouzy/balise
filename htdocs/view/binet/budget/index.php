<?php

  $budget_view = true;
  $table_title = "Résumé de la trésorerie du binet";
  ob_start();
?>
 <!-- TODO : DRY it up -->
  <thead>
    <tr>
      <th>Budget</th>
      <th>Tags</th>
      <th>Prévisionnel</th>
      <th>Réel</th>
      <th>Subventionné (accordé)</th>
      <th>Subventionné (utilisé)</th>
    </tr>
  </thead>
  <thead class="separator">
    <tr>
      <td colspan="6">Dépenses</td>
    </tr>
  </thead>
  <tbody>
    <?php
      $sum_amount_exp = 0;
      $sum_real_amount_exp = 0;
      $sum_subsidized_amount_granted_exp = 0;
      $sum_subsidized_amount_used_exp = 0;

      foreach ($budgets as $budget) {
        if ($budgets["amount"] < 0) {
          $sum_amount_exp += $budget["amount"];
          $sum_real_amount_exp += $budget["real_amount"];
          $sum_subsidized_amount_granted_exp += $budget["subsidized_amount_granted"];
          $sum_subsidized_amount_used_exp += $budget["subsidized_amount_used"];

        ?>
          <tr>
            <td><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($binet["id"], $term)), $budget["label"]); ?></td>
            <td><?php echo pretty_tags(select_tags_budget($budget["id"]), true); ?></td>
            <td><?php echo pretty_amount($budget["amount"]); ?></td>
            <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_granted"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
          </tr>
        <?php
        }
      }
    ?>
    <tr class="total">
      <td colspan="2">Total des dépenses</td>
      <td><?php echo pretty_amount($sum_amount_exp); ?></td>
      <td><?php echo pretty_amount($sum_real_amount_exp); ?></td>
      <td><?php echo pretty_amount($sum_subsidized_amount_granted_exp); ?></td>
      <td><?php echo pretty_amount($sum_subsidized_amount_used_exp); ?></td>
    </tr>
  </tbody>
  <thead class="separator">
      <tr>
          <td colspan="6">Recettes</td>
      </tr>
  </thead>
  <tbody>
    <?php
      $sum_amount_inc = 0;
      $sum_real_amount_inc = 0;
      $sum_subsidized_amount_granted_inc = 0;
      $sum_subsidized_amount_used_inc = 0;

      foreach ($budgets as $budget) {
        if ($budgets["amount"] > 0) {
          $sum_amount_inc += $budget["amount"];
          $sum_real_amount_inc += $budget["real_amount"];
          $sum_subsidized_amount_granted_inc += $budget["subsidized_amount_granted"];
          $sum_subsidized_amount_used_inc += $budget["subsidized_amount_used"];

        ?>
          <tr>
            <td><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($binet["id"], $term)), $budget["label"]); ?></td>
            <td><?php echo pretty_tags(select_tags_budget($budget["id"]), true); ?></td>
            <td><?php echo pretty_amount($budget["amount"]); ?></td>
            <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_granted"]); ?></td>
            <td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
          </tr>
        <?php
        }
      }
    ?>
    <tr class="total">
      <td colspan="2">Total des recettes</td>
      <td><?php echo pretty_amount($sum_amount_inc); ?></td>
      <td><?php echo pretty_amount($sum_real_amount_inc); ?></td>
      <td><?php echo pretty_amount($sum_subsidized_amount_granted_inc); ?></td>
      <td><?php echo pretty_amount($sum_subsidized_amount_used_inc); ?></td>
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
          <td><?php echo pretty_amount($sum_amount_inc + $sum_amount_exp); ?></td>
          <td><?php echo pretty_amount($sum_real_amount_inc + $sum_amount_exp); ?></td>
          <td><?php echo pretty_amount($sum_subsidized_amount_granted_inc + $sum_subsidized_amount_granted_exp); ?></td>
          <td><?php echo pretty_amount($sum_subsidized_amount_used_inc + $sum_subsidized_amount_used_exp); ?></td>
      </tr>
  </tbody>


<?php
  $table = ob_get_clean();

  include VIEW_PATH."binet/finances.php";
