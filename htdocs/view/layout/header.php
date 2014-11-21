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
