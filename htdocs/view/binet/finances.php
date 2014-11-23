<div class="row">
  <div class="col-lg-3">
  </div>
  <div class="col-lg-6">
    <form class="form">
      <!--TODO-->
      <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
      <input type="search" class="form-control pull-left">
    </form>
  </div>
  <div class="col-lg-3">
    <div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-animate bootstrap-switch-id-switch-state bootstrap-switch-off">
      <div class="bootstrap-switch-container">
        <?php
          $active = "bootstrap-switch-handle-on bootstrap-switch-default";
          $inactive = "bootstrap-switch-handle-off bootstrap-switch-primary";
          if ($budget_view) {
            $budget_class = $active;
            $operation_class = $inactive;
          } else {
            $budget_class = $inactive;
            $operation_class = $active;
          }
        ?>
        <?php echo link_to(path("index", "budget", "", binet_prefix($binet["id"], $term)), "Budget", $budget_class); ?>
        <label class="bootstrap-switch-label">&nbsp;</label>
        <?php echo link_to(path("index", "operation", "", binet_prefix($binet["id"], $term)), "Opérations", $operation_class); ?>

        <!-- C'est quoi ça ? -->
        <input id="switch-state" type="checkbox" checked data-on-text="Budget" data-off-text="Opérations">
      </div>
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
