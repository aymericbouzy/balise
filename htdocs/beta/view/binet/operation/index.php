<?php

  $budget_view = false;
  $table_title = "Dernières opérations";
  ob_start();
?>
<thead>
  <tr>
    <th>Nom</th>
    <th>Tags</th>
    <th>Date</th>
    <th>+</th>
    <th>-</th>
  </tr>
</thead>
<tbody>
  <?php
    $sum_revenue = 0;
    $sum_expenses = 0;
    foreach ($operations as $operation) {
      ob_start();
      ?>
        <td>
          <?php echo $operation["comment"]; ?>
        </td>
        <td>
          <?php echo pretty_tags(select_tags_operation($operation["id"]), true); ?>
        </td>
        <td>
          <?php echo $operation["date"]; ?>
        </td>
        <?php if ($operation["amount"] > 0) {
          $sum_revenue += $operation["amount"];
          ?><td></td><td>
            <?php echo pretty_amount($operation["amount"]); ?>
          </td><?php
        } else {
          $sum_expenses += $operation["amount"];
          ?><td>
            <?php echo pretty_amount($operation["amount"]); ?>
          </td><td></td>
      <?php
        }
      echo link_to(path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"])),
      "<tr>".ob_get_clean()."</tr>",array("goto"=>true));
    }
  ?>
</tbody>
<thead class="separator">
  <tr>
    <td colspan="5"></td>
  </tr>
</thead>
<tbody>
  <tr class="total">
    <td colspan="3">Total</td>
    <td><?php echo pretty_amount($sum_expenses); ?></td>
    <td><?php echo pretty_amount($sum_revenue); ?></td>
  </tr>
  <tr class="total">
    <td colspan="3">Solde</td>
    <td colspan="2"><?php echo pretty_amount($sum_revenue + $sum_expenses); ?></td>
  </tr>
</tbody>
<?php
  $table = ob_get_clean();

  include VIEW_PATH."binet/finances.php";
