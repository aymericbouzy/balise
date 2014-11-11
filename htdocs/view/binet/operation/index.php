<h1>Comptes</h1>
<div>
  <input type="text" name="search" value="">
  <div class="btn">
    Filtrer
  </div>
  <div id="filters">

  </div>
  <div class="switch">
    <div class="btn">
      Budget
    </div>
    <div class="btn selected">
      Opérations
    </div>
  </div>
</div>
<table>
  <?php foreach ($operations as $operation) {
    ?>
      <tr>
        <td>
          <?php echo $operation["comment"];
          foreach (select_tags_operation($operation["id"]) as $tag) {
            $tag = select_tag($tag, array("name", "id"));
            ?><span class="label"><?php echo $tag["name"]?></span><?php
          } ?>
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
          </td><td></td><?php
        } ?>
      </tr>
    <?php
  }
  ?>
  <tr>
    <td>
      Total
    </td>
    <td></td>
    <td>
      <?php echo pretty_amount($sum_expenses); ?>
    </td>
    <td>
      <?php echo pretty_amount($sum_revenue); ?>
    </td>
  </tr>
  <tr>
    <td>
      Solde
    </td>
    <td></td>
    <td>
      <?php echo pretty_amount($sum_expenses + $sum_revenue); ?>
    </td>
  </tr>
</table>
