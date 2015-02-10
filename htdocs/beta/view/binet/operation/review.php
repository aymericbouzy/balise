
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
        <thead>
          <tr>
            <td>Budget</td>
            <td>Tags</td>
            <td>Prévisionnel</td>
            <td>Réel</td>
            <td>Subventionné</td>
            <td>Utilisé</td>
            <td class="blue-background white-text" id="todo-user"><b>Remplissez le montant de l'opération se rapportant au budget</b></td>
          </tr>
        </thead>
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
                  <td class="light-blue-background"><?php echo form_group_text("", adds_amount_prefix($budget), $operation, "operation", array("onkeyup" => "total()", "onchange" => "total()", "class" => "amount-input")); ?></td>
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
        <?php echo "<script> var operation_amount = parseFloat(".pretty_amount($operation["amount"],false,false)."); </script>"; ?>
        <tbody>
          <tr class="total" id="remaining_amount_line">
            <td colspan="5">Montant restant à attribuer</td>
            <td id="amount-sum"><?php echo pretty_amount($operation["amount"],false,false); ?></td>
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
            var total_remaining = document.getElementById('amount-sum');
            var remaining = parseInt((operation_amount-s)*100)/100;
            total_remaining.innerHTML = remaining;

            var table_line = document.getElementById('remaining_amount_line');
            table_line.style.color = "#FFFFFF";
            if(remaining != 0){
              table_line.style.backgroundColor = "#D32F2F";
            }else{
              table_line.style.backgroundColor = "#4CAF50";
              document.getElementById('todo-user').backgroundColor = "#4CAF50";
            }
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
