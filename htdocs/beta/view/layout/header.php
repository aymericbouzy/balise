<div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <?php echo link_to(path("", "home"), "Balise", array("class" => "navbar-brand")); ?>
</div>
<ul class="nav navbar-right top-nav">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="fa fa-plus-circle green-plus" id="operation-plus"></i> <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
      <?php
        if (isset($_GET["prefix"]) && $_GET["prefix"] == "binet" && has_editing_rights($binet, $term)) {
          $budgets_for_checking_if_not_empty = select_budgets(array("binet" => $binet, "term" => $term));
          if (!is_empty($budgets_for_checking_if_not_empty)) {
            ?>
              <li class="add-operation">
                <?php echo link_to(path("new", "operation", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-calculator\"></i> Opération", array("class" => "add-operation")); ?>
              </li>
            <?php
          }
        ?>
        <li class="add-operation">
          <?php echo link_to(path("new", "budget", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-bar-chart\"></i> Ligne budgétaire", array("class" => "add-operation")); ?>
        </li >
        <li class="add-operation">
          <?php echo modal_toggle("request","<i class=\"fa fa-fw fa-question\"></i>Demander des subventions","add-operation","wave-select");?>
        </li>
        <?php
          if (select_binet($binet, array("subsidy_provider"))["subsidy_provider"] == 1) {
            ?>
            <li class = "divider"></li>
            <li class="add-operation">
              <?php echo link_to(path("new", "wave", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-money\"></i> Vague de subvention", array("class" => "add-operation")); ?>
            </li>
            <?php
          }
        } else {
          ?>
          <li class="add-operation">
            <?php echo link_to(path("new", "operation"), "<i class=\"fa fa-fw fa-calculator\"></i> Opération", array("class" => "add-operation")); ?>
          </li>
          <?php
        }
        if (is_current_kessier()) {
          ?>
          <li class = "divider"></li>
          <li class="add-operation">
            <?php echo link_to(path("new", "binet"), "<i class=\"fa fa-fw fa-group\"></i> Binet", array("class" => "add-operation")); ?>
          </li>
          <?php
        }
      ?>
    </ul>
  </li>

  <li>
    <span><?php echo pretty_student(connected_student(),true,true); ?></span>
  </li>
  <li style="padding-right:20px;">
		<?php echo link_to(path("logout", "home"), "<i class=\"fa fa-fw fa-power-off\" style=\"color:#fff;\"></i>") ?>
  </li>
  <?php
    if (isset($_GET["prefix"]) && $_GET["prefix"] == "binet" && has_editing_rights($binet, $term)) {
        ob_start();
        $waves_for_modal = select_waves(array("state" => "submission"), "submission_date", false);
        if (is_empty($waves_for_modal)) {
          echo "<i>Il n'y a aucune vague de subvention pour laquelle faire une demande en ce moment.</i>";
        } else {
          foreach ($waves_for_modal as $wave_for_modal) {
            echo link_to(path("new", "request", "", binet_prefix($binet,$term), array("wave" => $wave_for_modal["id"])),pretty_wave($wave_for_modal["id"],false),array("class" => "modal-list-element opanel0"));
          }
        }
        echo modal("wave-select","Sélectionner une vague de subventions : ",ob_get_clean());
    }
  ?>
</ul>
