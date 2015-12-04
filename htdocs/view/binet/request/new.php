<div class="show-container">
  <div class="panel shadowed light-blue-background">
    <div class="content">
    <h1><i class="fa fa-fw fa-plus-square"></i> Nouvelle demande de subvention</h1>
  </div>
  </div>
  <?php echo get_html_form("request_entry"); ?>
  <div class="panel shadowed light-blue-background">
    <?php
    $collapse_control_content = "<div class=\"title-small\">
      Ajouter une ligne de budget <i class=\"fa fa-fw fa-chevron-down\"></i>
    </div>";
    echo make_collapse_control($collapse_control_content, "subsidies_list");
    ?>
    <div class="collapse" id="subsidies_list">
      <div class="content">
        <?php echo get_html_form("budget"); ?>
      </div>
    </div>
  </div>
</div>
