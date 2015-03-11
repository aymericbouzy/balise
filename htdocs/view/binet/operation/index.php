<?php
  $budget_view = false;
  $table_title = "Dernières opérations";
  $sum_incomes = sum_array($operations, "amount", "positive");
  $sum_spendings = sum_array($operations, "amount", "negative");
  $balance = sum_array($operations, "amount");
  $sum_pending_incomes = sum_array($op_pending_kes_validations, "amount", "positive");
  $sum_pending_spendings = sum_array($op_pending_kes_validations, "amount", "negative");
  $pendings_balance = sum_array($op_pending_kes_validations, "amount");

  function operation_line($operation){
    $line = "<td class=\"element_name\">".$operation["comment"]."</td>
    <td class=\"tags\">".pretty_tags(select_tags_operation($operation["id"]), true)."</td>
    <td>".pretty_date($operation["date"])."</td>".
    (($operation["amount"] < 0) ? ("<td></td><td>".pretty_amount($operation["amount"],false)."</td>"):
    ("<td>".pretty_amount($operation["amount"],false)."</td><td></td>"));

    return link_to(path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"])),
    "<tr>".$line."</tr>",array("goto"=>true));
  }
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
<tbody class="list">
  <?php
    foreach ($operations as $operation) {
      echo operation_line($operation);
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
    <td><?php echo pretty_amount($sum_incomes,false); ?></td>
    <td><?php echo pretty_amount($sum_spendings,false); ?></td>
  </tr>
  <tr class="total">
    <td colspan="3">Solde</td>
    <td colspan="2"><b><?php echo pretty_amount($balance); ?></b></td>
  </tr>
</tbody>
<thead class="separator">
  <tr>
    <td colspan="5"></td>
  </tr>
</thead>
<tbody>
  <tr class="grey-background">
    <td colspan="5">
      Opérations en attente de validation par la Kès
    </td>
  </tr>
  <?php foreach($op_pending_kes_validations as $operation ){
      echo operation_line($operation);
    } ?>
</tbody>
<thead class="separator">
  <tr>
    <td colspan="5"></td>
  </tr>
</thead>
<tbody>
  <tr class="total">
    <td colspan="3">Total après validation des opérations en attente</td>
    <td><?php echo pretty_amount($sum_incomes + $sum_pending_incomes,false); ?></td>
    <td><?php echo pretty_amount($sum_spendings + $sum_pending_spendings,false); ?></td>
  </tr>
  <tr class="total">
    <td colspan="3">Solde après validation des opérations en attente</td>
    <td colspan="2"><b><?php echo pretty_amount($balance + $pendings_balance); ?></b></td>
  </tr>
</tbody>
<?php
  $table = ob_get_clean();

  include VIEW_PATH."binet/finances.php";
