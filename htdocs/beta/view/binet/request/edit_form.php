<form role="form" id="request" action="/<?php echo path($form_action, "request", $form_action == "create" ? "" : $request["id"], binet_prefix($binet, $term)); ?>" method="post">
  <table>
    <tr>
      <th>
        Budget
      </th>
      <th>
        Montant demandé
      </th>
      <th>
        Commentaire
      </th>
    </tr>
    <?php
      foreach ($budgets_involved as $budget) {
        ?>
        <tr>
          <td>
            <?php echo pretty_budget($budget["id"]); ?>
          </td>
          <td>
            <?php echo form_group_text("", adds_amount_prefix($budget), $request, "request"); ?>
          </td>
          <td>
            <?php echo form_group_text("", adds_purpose_prefix($budget), $request, "request"); ?>
          </td>
        </tr>
        <?php
      }
    ?>
  </table>

  <?php echo form_group_text($request["wave"]["question"], "answer", $request, "request"); ?>
  <?php echo form_hidden("wave", $request["wave"]["id"]); ?>
  <?php echo form_csrf_token(); ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
