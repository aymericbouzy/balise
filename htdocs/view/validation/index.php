<div id="index-wrapper">
  <div class="panel shadowed">
    <?php
    if (sizeOf($pending_validations_operations_kes) > 0) {
      ?>
      <div class="title">Opérations à valider par la Kès</div>
      <div class="content">
        <div class="table-responsive" id="validations-table-kes">
          <table class="table table-bordered table-hover table-small-char">
            <thead>
              <tr>
                <th>Date</th>
                <th>Nom</th>
                <th>Origine</th>
                <th>Montant</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($pending_validations_operations_kes as $operation) {
                  $operation = select_operation($operation["id"], array("id", "date", "comment", "created_by", "amount", "binet", "term"));
                  ob_start();
                  ?>
                  <tr>
                    <td><?php echo pretty_date($operation["date"]); ?></td>
                    <td><?php echo $operation["comment"]; ?></td>
                    <td><?php echo pretty_student($operation["created_by"])." ".pretty_binet_term(term_id($operation["binet"], $operation["term"])); ?></td>
                    <td><?php echo pretty_amount($operation["amount"]); ?></td>
                  </tr>
                  <?php
                  echo link_to(path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"])), ob_get_clean(), array("goto" => true));
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php
    } else {
      ?>
      <div class="content light-blue-background">
        Aucune validation en attente pour la Kès !
      </div>
      <?php
      }
    ?>
  </div>
</div>
