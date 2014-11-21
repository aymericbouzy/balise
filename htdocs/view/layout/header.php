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
      <li><a href="#">Binet massage</a></li>
      <li><a href="#">X-Circus</a></li>
      <li><a href="#">JTX</a></li>
    </ul>
  </li>
</ul>
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-plus-circle green-plus" id="operation-plus"></i> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="add-operation">
                                <a href="#"><i class="fa fa-fw fa-calculator"></i> Opération</a>
                            </li>
                            <li>
                                <a class="add-operation" href="#"><i class="fa fa-fw fa-bar-chart"></i> Ligne budgétaire</a>
                            </li >
                            <li class="add-operation">
                                <a class="add-operation" href="#"><i class="fa fa-fw fa-money"></i> Demande de subvention</a>
                            </li>
                            <li class="divider"></li>
                            <li class="add-operation">
                                <a class="add-operation" href="#"><i class="fa fa-fw fa-group"></i> Binet</a>
                            </li>
                        </ul>

                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Robert Blanquette <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Autorisations</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
<div id="header-left">
  <ul>
    <li>
      <?php echo link_to(path("", "binet", binet_term_id($binet["id"], $term)), $binet["name"]."<span class=\"binet-term\">".$term."</span>"); ?>
      <ul>
        <?php foreach(binet_admins_current_student() as $binet_admin) {
          $binet_admin["name"] = select_binet($binet_admin["binet"], array("name"))["name"];
          ?>
          <li>
            <?php echo link_to(path("", "binet", binet_term_id($binet_admin["binet"], $binet_admin["term"])), $binet_admin["name"]."<span class=\"binet-term\">".$binet_admin["term"]."</span>"); ?>
          </li>
          <?php
        }
        ?>
      </ul>
    </li>
    <li>
      <i class="fa-plus"></i>
      <ul>
        <li>
          <?php echo link_to(path("new", "budget", "", binet_prefix($binet["id"], $term)), "Ligne budgétaire"); ?>
        </li>
        <li>
          <?php echo link_to(path("new", "operation", "", binet_prefix($binet["id"], $term)), "Opération"); ?>
        </li>
        <li>
          <?php echo link_to(path("new", "request", "", binet_prefix($binet["id"], $term)), "Demande de subvention"); ?>
        </li>
        <?php if (select_binet($binet["id"], array("subsidy_provider"))["subsidy_provider"] == 1) {
          ?>
          <li class = "seperator"></li>
          <li>
            <?php echo link_to(path("new", "wave", "", binet_prefix($binet["id"], $term)), "Vague de subvention"); ?>
          </li>
          <?php
        }
        if ($binet["id"] == $KES_ID) {
          ?>
          <li class = "seperator"></li>
          <li>
            <?php echo link_to(path("new", "binet"), "Binet"); ?>
          </li>
          <?php
        }
        ?>
      </ul>
    </li>
  </ul>
</div>
<div id="header-center">
  <?php echo link_to(path(), img("??")) ?>
</div>
<div id="header-right">
  <div id="header-name">
    <?php echo $current_student["name"]; ?>
  </div>
  <?php echo link_to(path("logout", "home"), "<i class=\"fa-logout\" alt=\"logout\"></i>") ?>
</div>
