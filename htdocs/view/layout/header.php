<div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <?php echo link_to(path("", "home"), "<i class=\"fa fa-fw fa-home\"></i> Balise", array("class" => "navbar-brand")); ?>
</div>
<ul class="nav navbar-right top-nav">
  <li>
    <?php
    $help_file = VIEW_PATH."help/".(is_empty($_GET["prefix"]) ? "" : $_GET["prefix"]."/").$_GET["controller"]."/".$_GET["action"].".php";
    if (file_exists($help_file)) {
      echo modal_toggle("help", "<span class=\"label label-warning\">Aide</span>", "", "display-help");
    }
    ?>
  </li>
  <?php
  if (isset($_GET["prefix"]) && $_GET["prefix"] == "binet" && has_editing_rights($binet, $term)) {
    ?>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-plus-circle operation-plus"></i> <span class="caret"></span>
      </a>
      <ul class="dropdown-menu" role="menu">
        <?php
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
    <?php
  }
  ?>
  <?php
  if ($_GET["controller"] != "home") {
    ?>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i style="color:#3399FF" class="fa fa-fw fa-group"></i> 	<span class="caret"></span>
      </a>
      <ul class="dropdown-menu" role="menu">
        <?php
        foreach (select_terms(array("student" => $_SESSION["student"])) as $term_admin) {
          $term_admin = select_term_binet($term_admin["id"], array("id", "binet", "term"))
          ?>
            <li>
              <?php
                $link = in_array($_GET["controller"], array("budget", "operation", "member", "request")) ?
                  path("", $_GET["controller"], "", binet_prefix($term_admin["binet"], $term_admin["term"])) :
                  path("", "binet", binet_term_id($term_admin["binet"], $term_admin["term"]));
                echo link_to($link, pretty_binet_term($term_admin["id"], false));
              ?>
            </li>
          <?php
        }
        ?>
      </ul>
    </li>
    <?php
  }
  ?>
  <li>
    <?php
    if (is_current_kessier()) {
      $count_pending_validations_kes = count_pending_validations_kes();
      echo link_to(path("", "validation"), "<i class=\"fa fa-fw fa-desktop\" style=\"color:#fff;\"></i>".($count_pending_validations_kes > 0 ? "<span class=\"counter counter-sm shadowed0\">".$count_pending_validations_kes."</span>" : ""));
    }
    ?>
  </li>
  <li>
    <span><?php echo pretty_student(connected_student(),true,true); ?></span>
  </li>

  <li>
		<?php echo link_to(path("logout", "home"), "<i class=\"fa fa-fw fa-power-off\" style=\"color:#fff;\"></i>") ?>
  </li>

  <!-- Modal : the user can choose the wave to ask for subsidies (make a request) -->
  <?php
    if (isset($_GET["prefix"]) && $_GET["prefix"] == "binet" && has_editing_rights($binet, $term)) {
        ob_start();
        $waves_for_modal = select_waves(array("state" => "submission"), "submission_date", false);
        if (is_empty($waves_for_modal)) {
          echo "<i>Il n'y a aucune vague de subvention pour laquelle faire une demande en ce moment.</i>";
        } else {
          foreach ($waves_for_modal as $wave_for_modal) {
            echo link_to(path("new", "request", "", binet_prefix($binet,$term), array("wave" => $wave_for_modal["id"])),pretty_wave($wave_for_modal["id"],false),array("class" => "modal-list-element shadowed0"));
          }
        }
        echo modal("wave-select", ob_get_clean(), array("title" => "Sélectionner une vague de subventions : "));
    }
    if (file_exists($help_file)) {
      ob_start();
      include $help_file;
      echo modal("display-help", ob_get_clean(),array("title" => "Aide"));
    }
  ?>
</ul>
