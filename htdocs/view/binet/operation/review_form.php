<div class="bigpanel shadowed">
  <div class="title">Opération</div>
  <div class="table-responsive panel-content" id="validations-table">
    <table class="table table-bordered table-small-char">
      <thead>
        <tr>
          <td>Date d'émission</td>
          <td>Intitulé</td>
          <td> A posté l'opération </td>
          <td> Montant de l'opération</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo pretty_date($operation["date"]); ?></td>
          <td><?php echo $operation["comment"]; ?></td>
          <td><?php echo pretty_student($operation["created_by"])." ".pretty_binet_term($operation["binet"]."/".$operation["term"]); ?></td>
          <td><?php echo pretty_amount($operation["amount"]); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="panel" id="review_form_info">
    <div class="title-small">
      Informations complémentaires
    </div>
    <div class="content">
      <div class="panel-line">
        <?php echo insert_tooltip("<span>".($operation["paid_by"] ? paid_by_to_caption($operation["paid_by"]) : "Aucun payeur enregistré")."</span>","Payé par"); ?>
        <?php echo insert_tooltip("<span>".pretty_tags(select_tags_operation($operation["id"]), false)."</span>","Mots clés courants"); ?>
        <?php echo insert_tooltip("<span><i class=\"fa fa-fw fa-folder-o\"></i>".($operation["bill"] ?: "Aucune facture associée")."</span>","Référence de facture") ?>
        <?php echo insert_tooltip("<span>".pretty_operation_type($operation["type"])." ".($operation["payment_ref"] ?: "Aucune référence de paiement associée")."</span>","Référence de paiement"); ?>
      </div>
    </div>
    <div class="content">
      <?php
        echo link_to(path("edit", "operation", $operation["id"], binet_prefix($binet, $term)),"Modifier l'opération",array("class"=>"btn","id"=>"edit-btn"));
        echo link_to(path("delete", "operation", $operation["id"], binet_prefix($binet, $term), array(), true),"Supprimer l'opération",array("class"=>"btn btn-danger","id"=>"suppr-btn"));
      ?>
    </div>
  </div>
</div>

<div class="bigpanel shadowed">
  <span class="title">Budgets - <i style="font-size:13px">répartir le montant de l'opération entre les différents budgets </i></span>
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
                <td class="light-blue-background"><?php echo form_input("", "amount_".$budget["id"], $form, array("html_decoration" => array("onkeyup" => "total()", "onchange" => "total()", "class" => "amount-input"))); ?></td>
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
            var amount = parseInt(parseFloat(inputs[i].value) * 100);
            if (inputs[i] != null && inputs[i].value != null && !isNaN(amount)) {
              s += amount;
            }
          }
          var total_remaining = document.getElementById('amount-sum');
          var remaining = (operation_amount * 100 - s)/100;
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
  <div class="buttons">
    <?php echo form_submit_button("Enregistrer"); ?>
    <?php echo link_to(path("delete", "operation", $operation["id"], binet_prefix($binet, $term), array(), true), "Supprimer l'opération", array("class" => "btn btn-danger")); ?>
  </div>
</div>
