<div class="row">
  <div class="col-lg-1 col-md-1 col-sm-0"></div>
  <div class="col-lg-10 col-md-10 col-sm-12">
    <div class="panel transparent-background">
      <div class="content" id="controlbar">
        <div id="select-term">
          <?php echo modal_toggle("choose-term", "Promo ".term."<i class=\"fa fa-fw fa-caret-square-o-down\"></i>","shadowed0 blue-background white-text","terms"); ?>
        </div>
        <div id="view-binet">
          <?php
            echo insert_tooltip(
              link_to(path("show","binet",binet),"<i class=\"fa fa-fw fa-eye\"></i>",array("class" => "btn btn-success")),
              "Voir le binet"
            );
          ?>
        </div>
        <?php
        if (has_editing_rights(binet, term) && is_transferable()) {
          ?>
          <div id="transfer_budgets">
            <?php
            if (sizeOf($budgets) == 0) {
              echo link_to(
                path("transfer", "budget", "", binet_prefix(binet, term)),
                "<i class=\"fa fa-fw fa-arrow-down\"></i> Importer des budgets du mandat précédent",
                array("class" => "btn")
              );
            } else {
              echo insert_tooltip(
                link_to(
                  path("transfer", "budget", "", binet_prefix(binet, term)),
                  "<i class=\"fa fa-fw fa-arrow-down\"></i>",array("class" => "btn btn-discrete")),
                "Importer des budgets du mandat précédent"
              );
            }
            ?>
          </div>
          <?php
          }
        ?>
      </div>
    </div>
    <div class="panel shadowed">
      <div class="title">Résumé de la trésorerie du binet</div>
      <div class="content">
        <div id="searchlist">
          <?php echo search_input(); ?>
          <table class="table table-bordered table-hover table-small-char">
            <thead>
              <tr>
                <th colspan=2 >Budget</th>
                <th colspan=2 >Montant</th>
                <th colspan=3 >Subventions</th>
              </tr>
              <tr>
                <th>Nom</th>
                <th>Mots-clefs</th>
                <th>Prévisionnel</th>
                <th>Réel</th>
                <th>Attendues</th>
                <th>Disponibles</th>
                <th>Utilisées</th>
              </tr>
            </thead>
            <thead class="separator">
              <tr>
                <td colspan="7">Dépenses</td>
              </tr>
            </thead>
            <tbody class="list">
              <?php
                foreach ($budgets as $budget) {
                  if ($budget["amount"] < 0) {
                    ?>
                    <tr>
                      <td class="element_name"><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix(binet, term)), $budget["label"]); ?></td>
                      <td class="tags"><?php echo pretty_tags(select_tags_budget($budget["id"]), true); ?></td>
                      <td><?php echo pretty_amount($budget["amount"]); ?></td>
                      <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
                      <td><?php echo pretty_amount($budget["subsidized_amount"]); ?></td>
                      <td><?php echo pretty_amount($budget["subsidized_amount_available"]); ?></td>
                      <td><?php echo pretty_amount($budget["subsidized_amount_used"]); ?></td>
                    </tr>
                  <?php
                  }
                }
                foreach ($waves as $wave) {
                  ?>
                  <tr class="budget-wave">
                    <td class="element_name"><?php echo pretty_wave($wave["id"]); ?></td>
                    <td></td>
                    <td><?php echo pretty_amount(-$wave["predicted_amount"]); ?></td>
                    <td><?php echo pretty_amount(-$wave["used_amount"]); ?></td>
                    <td class="grey-300-background" colspan="3"></td>
                  </tr>
                  <?php
                }
              ?>
              <tr class="total">
                <td colspan="2">Total des dépenses</td>
                <td><?php echo pretty_amount(sum_array($budgets, "amount", "negative") - sum_array($waves, "predicted_amount")); ?></td>
                <td><?php echo pretty_amount(sum_array($budgets, "real_amount", "negative") - sum_array($waves, "used_amount")); ?></td>
                <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount", "positive")); ?></td>
                <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_available", "positive")); ?></td>
                <td><?php echo pretty_amount(sum_array($budgets, "subsidized_amount_used", "positive")); ?></td>
              </tr>
            </tbody>
            <thead class="separator">
              <tr>
                <td colspan="4">Recettes</td>
              </tr>
            </thead>
            <tbody class="list">
              <?php
                foreach ($budgets as $budget) {
                  if ($budget["amount"] > 0) {
                    ?>
                    <tr>
                      <td class="element_name"><?php echo link_to(path("show", "budget", $budget["id"], binet_prefix(binet, term)), $budget["label"]); ?></td>
                      <td class="tags"><?php echo pretty_tags(select_tags_budget($budget["id"]), true); ?></td>
                      <td><?php echo pretty_amount($budget["amount"]); ?></td>
                      <td><?php echo pretty_amount($budget["real_amount"]); ?></td>
                      <td class="grey-300-background" colspan="3"></td>
                    </tr>
                  <?php
                  }
                }
              ?>
              <tr class="total">
                <td colspan="2">Total des recettes</td>
                <td><?php echo pretty_amount(sum_array($budgets, "amount", "positive")); ?></td>
                <td><?php echo pretty_amount(sum_array($budgets, "real_amount", "positive")); ?></td>
                <td class="grey-300-background" colspan="3"></td>
              </tr>
            </tbody>
            <thead class="separator">
              <tr>
                <td colspan="4"></td>
              </tr>
            </thead>
            <tbody>
              <tr class="total">
                <td colspan="2">Total (non-subventionné)</td>
                <td><?php echo pretty_amount(sum_array($budgets, "amount")); ?></td>
                <td><?php echo pretty_amount(sum_array($budgets, "real_amount")); ?></td>
                <td class="grey-300-background" colspan="3"></td>
              </tr>
            </tbody>
            <tbody>
              <tr class="total">
                <td colspan="2">Total</td>
                <td><?php echo pretty_amount(sum_array($budgets, "amount") + sum_array($budgets, "subsidized_amount") - sum_array($waves, "predicted_amount")); ?></td>
                <td><b><?php echo pretty_amount(sum_array($budgets, "real_amount") + sum_array($budgets, "subsidized_amount_used") - sum_array($waves, "used_amount")); ?></b></td>
                <td class="grey-300-background" colspan="3"></td>
              </tr>
            </tbody>
          </table>
          <?php echo tip(" Vous pouvez copier-coller les informations de ce tableau.") ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-1 col-md-1 col-sm-0"></div>
</div>
<?php echo modal("terms","Voir l'activité d'une autre promotion du binet",pretty_terms_list(binet)); ?>
<script src = "<?php echo ASSET_PATH; ?>js/list.js"></script>
<?php echo initialize_tablefilter("searchlist",array("element_name","tags")); ?>
