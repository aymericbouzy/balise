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
  <?php
    if ($form_action == "create") {
      $current_term_binet = current_term($binet);
      $checked = $current_term_binet != $term;
      $term_redirect = $current_term_binet + ($checked ? 0 : 1);
    ?>
    <div class="checkbox">
      <label>
        <input type="checkbox" onclick="goto('/<?php echo path("new", "request", "", binet_prefix($binet, $term_redirect), array("wave" => $request["wave"]["id"])); ?>')"<?php echo $checked ? " checked" : "" ?>>
        Faire la demande pour la promotion suivante
      </label>
    </div>
    <?php
    }
  ?>
  <?php echo form_submit_button($submit_label); ?>
</form>
