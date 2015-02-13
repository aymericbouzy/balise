<div class="row">
  <div class="col-lg-3">
    <!-- Select term modal -->
    <span class="opanel0" style="padding:5px;"id="choose-term" data-toggle="modal" data-target="#terms">
      <?php echo $term; ?><i class="fa fa-fw fa-caret-square-o-down"></i>
    </span>
    <div class="balise-modal fade" id="terms" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-content balise-modal-container">
        <div class="modal-body">
          <?php
          echo close_button("modal");?>
          <span class="header">Voir l'activité d'une autre promotion du binet</span>
          <div class="content">
            <?php echo pretty_terms_list($binet);?>
          </div>
        </div>
      </div>
    </div>
    <!-- End modal -->
  </div>
  <div class="col-lg-6">
    <?php echo fuzzy_input();?>
  </div>
  <div class="col-lg-3">
    <div class="switch opanel">
      <?php
      $active = "active";
      $inactive = "inactive";
      if ($budget_view) {
        $budget_class = $active;
        $operation_class = $inactive;
      } else {
        $budget_class = $inactive;
        $operation_class = $active;
      }
      ?>
      <span class="left component <?php echo $budget_class; ?> ">
        <?php echo link_to(path("index", "budget", "", binet_prefix($binet, $term)), "Budget",array("class" => $budget_class)); ?>
      </span>
      <span class="right component <?php echo $operation_class; ?>">
        <?php echo link_to(path("index", "operation", "", binet_prefix($binet, $term)), "Opérations",array("class" => $operation_class)); ?>
      </span>
    </div>
  </div>
</div>
<div class="row-centered">
  <div class="col-max">
    <h2 class="tabtitle"><?php echo $table_title; ?></h2>
    <div class="table-responsive" id="operations-table">
      <table class="table table-bordered table-hover table-small-char">
        <?php echo $table; ?>
      </table>
    </div>
  </div>
</div>
<?php echo fuzzy_load_scripts("",""); ?>
