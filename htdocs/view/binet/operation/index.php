<?php
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

<div id="index-wrapper">
  <div class="panel transparent-background">
    <div class="content" id="controlbar">
      <div id="select-term">
        <?php echo modal_toggle("choose-term", "Promo ".$term."<i class=\"fa fa-fw fa-caret-square-o-down\"></i>","shadowed0 blue-background white-text","terms"); ?>
      </div>
      <div id="view-binet">
        <?php echo insert_tooltip(
            link_to(path("show","binet",$binet),"<i class=\"fa fa-fw fa-eye\"></i>",array("class" => "btn btn-success")),
            "Voir le binet"); ?>
      </div>
    </div>
  </div>
  <div class="panel shadowed">
    <?php
    if (sizeOf($pending_validations_operations) > 0) {
      ?>
      <div class="title">Opérations en attente</div>
        <div class="content">
          <div class="table-responsive" id="validations-table">
            <table class="table table-bordered table-hover table-small-char">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Nom</th>
                  <th>Origine</th>
                  <th>Montant</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach ($pending_validations_operations as $operation) {
                    $operation = select_operation($operation["id"], array("id", "date", "comment", "created_by", "amount"));
                    ob_start();
                    ?>
                    <tr>
                      <td><?php echo pretty_date($operation["date"]); ?></td>
                      <td><?php echo $operation["comment"]; ?></td>
                      <td><?php echo pretty_student($operation["created_by"]); ?></td>
                      <td><?php echo pretty_amount($operation["amount"]); ?></td>
                      <td><?php echo insert_tooltip(
                        link_to(path("delete", "operation", $operation["id"], binet_prefix($binet, $term), array(), true),"<i class=\"fa fa-fw fa-times\"></i>"),"Supprimer");?></td>
                    </tr>
                    <?php
                    echo link_to(path("review", "operation", $operation["id"], binet_prefix($binet, $term)), ob_get_clean(), array("goto" => true));
                  }
                ?>
              </tbody>
            </table>
            <?php echo tip("Tu peux accéder à tes opérations en attente depuis l'accueil directement en cliquant sur le point rouge quand il apparait.");?>
          </div>
        </div>
        <?php
      }
    ?>
  </div>
  <div class="panel shadowed">
    <div class="title">Opérations</div>
    <div class="content">
      <div id="searchlist">
        <?php echo search_input();?>
        <table class="table table-bordered table-hover table-small-char">
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
        </table>
        <?php echo tip(" Vous pouvez copier-coller les informations de ce tableau.") ?>
      </div>
    </div>
  </div>
</div>
<?php echo modal("terms","Voir l'activité d'une autre promotion du binet",pretty_terms_list($binet)); ?>
<script src = "<?php echo ASSET_PATH; ?>js/list.js"></script>
<?php echo initialize_tablefilter("searchlist",array("element_name","tags")); ?>
