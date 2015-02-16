<div id="finances">
  <div class="panel transparent-background">
    <div class="content" id="controlbar">
      <div id="select-term">
        <?php echo modal_toggle("choose-term",$term."<i class=\"fa fa-fw fa-caret-square-o-down\"></i>","opanel0 blue-background white-text","terms"); ?>
      </div>
      <div id="view-binet">
        <?php echo link_to(path("show","binet",$binet),"<i class=\"fa fa-fw fa-eye\"></i>",array("class" => "btn btn-success")); ?>
      </div>
      <div id="fuzzy-input">
        <?php echo fuzzy_input();?>
      </div>
      <div class="switch opanel" id="switch-operations-budgets">
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
  <div class="panel opanel">
    <div class="title"><?php echo $table_title; ?></div>
    <div class="content">
      <div>
        <table class="table table-bordered table-hover table-small-char">
          <?php echo $table; ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php echo modal("terms","Voir l'activité d'une autre promotion du binet",pretty_terms_list($binet)); ?>
<?php echo fuzzy_load_scripts("",""); ?>
