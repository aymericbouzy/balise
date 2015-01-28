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
      <?php if (isset($_GET["prefix"]) && $_GET["prefix"] == "binet") {
          if (!empty(select_budgets(array("binet" => $binet, "term" => $term)))) {
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
          <?php
          if (!empty(select_budgets(array("binet" => $binet, "term" => $term))) && !empty(select_waves(array("state" => "submission")))) {
            ?>
              <li class="add-operation">
                <?php echo link_to(path("new", "request", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-money\"></i> Demande de subvention", array("class" => "add-operation")); ?>
              </li>
            <?php
          }
          if (select_binet($binet, array("subsidy_provider"))["subsidy_provider"] == 1) {
            ?>
            <li class = "divider"></li>
            <li>
              <?php echo link_to(path("new", "wave", "", binet_prefix($binet, $term)), "<i class=\"fa fa-fw fa-money\"></i> Vague de subvention", array("class" => "add-operation")); ?>
            </li>
            <?php
          }
          if ($binet == KES_ID) {
            ?>
            <li class = "divider"></li>
            <li class="add-operation">
              <?php echo link_to(path("new", "binet"), "<i class=\"fa fa-fw fa-group\"></i> Binet", array("class" => "add-operation")); ?>
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
      ?>
    </ul>
  </li>

  <li>
    <span><i class="fa fa-user"></i> <?php echo $current_student["name"]; ?></span>
  </li>
  <li style="padding-right:20px;">
		<?php echo link_to(path("logout", "home"), "<i class=\"fa fa-fw fa-power-off\" style=\"color:#fff;\"></i>") ?>
  </li>
</ul>
