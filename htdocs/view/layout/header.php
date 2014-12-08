<div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <?php echo link_to(path("", "home"), "Balise", "navbar-brand"); ?>
</div>

<ul class="nav navbar-left top-nav">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">#nomDuBinet</a>
    <ul class="dropdown-menu" role="menu">
      <?php foreach(binet_admins_current_student() as $binet_admin) {
        $binet_admin["binet_name"] = select_binet($binet_admin["binet"], array("name"))["name"];
        ?>
        <li>
          <?php echo link_to(path("", "binet", binet_term_id($binet_admin["binet"], $binet_admin["term"])), $binet_admin["binet_name"]."<span class=\"binet-term\">".$binet_admin["term"]."</span>"); ?>
        </li>
        <?php
      }
      ?>
    </ul>
  </li>
</ul>


<ul class="nav navbar-right top-nav">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="fa fa-plus-circle green-plus" id="operation-plus"></i> <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
      <li class="add-operation">
        <?php echo link_to(path("new", "operation", "", binet_prefix($binet["id"], $term)), "<i class=\"fa fa-fw fa-calculator\"></i> Opération", "add-operation"); ?>
      </li>
      <li>
        <?php echo link_to(path("new", "budget", "", binet_prefix($binet["id"], $term)), "<i class=\"fa fa-fw fa-bar-chart\"></i> Ligne budgétaire", "add-operation"); ?>
      </li >
      <li class="add-operation">
        <?php echo link_to(path("new", "request", "", binet_prefix($binet["id"], $term)), "<i class=\"fa fa-fw fa-money\"></i> Demande de subvention", "add-operation"); ?>
      </li>
      <?php if (select_binet($binet["id"], array("subsidy_provider"))["subsidy_provider"] == 1) {
        ?>
        <li class = "divider"></li>
        <li>
          <?php echo link_to(path("new", "wave", "", binet_prefix($binet["id"], $term)), "<i class=\"fa fa-fw fa-money\"></i> Vague de subvention", "add-operation"); ?>
        </li>
        <?php
      }
      if ($binet["id"] == $KES_ID) {
        ?>
        <li class = "divider"></li>
        <li>
          <?php echo link_to(path("new", "binet"), "<i class=\"fa fa-fw fa-group\"></i> Binet", "add-operation"); ?>
        </li>
        <?php
      }
      ?>
    </ul>
  </li>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $current_student["name"]; ?>e <b class="caret"></b></a>
    <ul class="dropdown-menu">
      <li>
        <!--TODO-->
        <a href="#"><i class="fa fa-fw fa-user"></i> Autorisations</a>
      </li>
      <li class="divider"></li>
      <li>
        <?php echo link_to(path("logout", "home"), "<i class=\"fa fa-fw fa-power-off\"></i> Déconnexion") ?>
      </li>
    </ul>
  </li>
</ul>
