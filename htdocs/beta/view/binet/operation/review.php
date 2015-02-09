
<div class="bigpanel opanel">
  <span class="title">Opération</span>
  <div class="table-responsive panel-content" id="validations-table">
    <table class="table table-bordered table-small-char">
      <tbody>
        <?php echo validatable_operation_line($operation, false); ?>
      </tbody>
    </table>
  </div>
</div>

<div class="bigpanel opanel">
  <span class="title">Budgets</span>
  <form role="form" id="operation" action="/<?php echo path("validate", "operation", $operation["id"], binet_prefix($binet, $term)); ?>" method="post">
    <div class="table-responsive panel-content" id="validations-table">
      <table class="table table-bordered table-hover table-small-char">
        <tbody>
          <?php
            foreach ($binet_budgets as $budget) {
              $budget = select_budget($budget["id"], array("id", "label", "amount", "real_amount", "subsidized_amount_granted", "subsidized_amount_used"));
              ?>
                <tr>
                  <td><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix($binet, $term)), $budget["label"]); ?></td>
                  <td><?php echo pretty_tags(select_tags_budget($budget["id"]), false); ?></td>
                  <td><?php echo pretty_amount($budget["amount"]); ?></td>
                  <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
                  <td><?php echo pretty_amount($budget["subsidized_amount_granted"]); ?></td>
                  <td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
                  <td><?php echo form_group_text(pretty_budget($budget["id"]), adds_amount_prefix($budget), $operation, "operation", array("onkeypress" => "total()", "onchange" => "total()", "class" => "amount-input")); ?></td>
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
        <tbody>
          <tr class="total">
            <td colspan="5">Total</td>
            <td id="amount-sum"><?php echo $operation["amount"]; ?></td>
          </tr>
        </tbody>
        <script>
          function total() {
            var inputs = document.getElementsByClassName('amount-input');
            var i, s;
            s = 0;
            for (i = 0; i < inputs.length; i++) {
              if (inputs[i] != null && inputs[i].value != null && !isNaN(parseFloat(inputs[i].value))) {
                console.log(parseFloat(inputs[i].value));
                s += parseFloat(inputs[i].value);
              }
            }
            document.getElementById('amount-sum').innerHTML = parseInt(s*100)/100;
          }
        </script>
        </tbody>
      </table>
    </div>
    <?php echo form_csrf_token(); ?>
    <div class="buttons">
      <?php echo form_submit_button("Enregistrer"); ?>
      <?php echo link_to(path("delete", "operation", $operation["id"], binet_prefix($binet, $term), array(), true), "Supprimer l'opération", array("class" => "btn btn-danger")); ?>
    </div>
  </form>
</div>
