<!-- TODO: solve conflict -->

<<<<<<< HEAD
<div class="show-container">
  <div class="sh-title">
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
  <div class="sh-op-amount">
    <?php echo pretty_amount($operation["amount"]); ?> <i class="fa fa-euro"></i>
  </div>
  <div class="sh-op-refs">
    <i class="fa fa-fw fa-folder-o"></i> </br>
    <?php echo $operation["bill"] ?: "Aucune facture associée"; ?> </br>
    <?php echo pretty_operation_type($operation["type"])." ".($operation["reference"] ?: "Aucune référence de paiement associée"); ?>
  </div>
  <div class="sh-op-info">
    <?php echo $operation["comment"]; ?>
  </div>
  <div class="sh-op-payer">
    <i class="fa fa-fw fa-user"></i> <?php echo $operation["paid_by"] ? pretty_student($operation["paid_by"]) : "Aucun payeur enregistré"; ?>
=======
<div class="row-centered">
  <div class="row-centered">
    <div class="col-max">
      <h2 class="tabtitle">Opération :</h2>

      <div class="table-responsive" id="validations-table">
        <table class="table table-bordered table-hover table-small-char">
          <tbody>
            <?php echo validatable_operation_line($operation, false); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row-centered">
  <div class="col-max">
    <h2 class="tabtitle">Budgets :</h2>

    <div class="table-responsive" id="validations-table">
      <table class="table table-bordered table-hover table-small-char">
        <tbody>
          <?php
            foreach ($budgets as $budget) {
              $budget = select_budget($budget["id"], array("id", "label", "amount", "real_amount", "subsidized_amount_granted", "subsidized_amount_used"));
              ?>
                <tr>
                  <td><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($binet, $term)), $budget["label"]); ?></td>
                  <td><?php echo pretty_tags(select_tags_budget($budget["id"]), false); ?></td>
                  <td><?php echo pretty_amount($budget["amount"]); ?></td>
                  <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
                  <td><?php echo pretty_amount($budget["subsidized_amount_granted"]); ?></td>
                  <td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
                  <?php
                    if ($operation["binet_validation_by"] == NULL) {
                      ?>
                        <td><input type="text" class="amount-input" name="<?php echo amount_prefix.$budget["id"]; ?>" onchange="total()"></td>
                      <?php
                    }
                  ?>
                </tr>
              <?php
            }
          ?>
        </tbody>
        <thead class="separator">
          <tr>
            <td colspan="7"></td>
          </tr>
        </thead>
        <?php
          if ($operation["binet_validation_by"] == NULL) {
            ?>
              <tbody>
                <tr class="total">
                  <td colspan="5">Total</td>
                  <td id="sum">0</td>
                </tr>
              </tbody>
            <?php
          }
        ?>
        <script>
          function total() {
            var inputs=document.getElementsByTagName('input');
            var i,s;
            s=0;
            for(i=1;i<=inputs.length;i++) {
              s+=inputs.item(i).innerHTML;
            }
            document.getElementById('sum').innerHTML=s;
          }
        </script>
        </tbody>
      </table>
    </div>
>>>>>>> master
  </div>
</div>
