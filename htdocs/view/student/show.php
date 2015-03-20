<div class="sidebar-not-present">
  <div class="show-container">
    <div class="sh-title shadowed">
      <div class="logo">
        <i class="fa fa-5x fa-user"></i>
      </div>
      <div class="text">
        <span class="main">
          <?php echo $student["name"]; ?>
        </span>
        <span class="sub">
          <?php echo link_to("mailto:".$student["name"]." <".$student["email"].">", $student["email"]); ?>
        </span>
      </div>
    </div>
    <div class="panel shadowed light-blue-background">
      <div class="title">
        Binets administrés
      </div>
      <div class="content">
        <?php
        foreach (select_terms(array("student" => $student["id"])) as $term){
          $term = select_term_binet($term["id"], array("id", "binet", "term"));
          $term["rights"] = status_member_term($term["id"]);
          echo link_to(path("show", "binet", $term["binet"]),
            "<div><i class=\"icon fa fa-fw fa-".($term["rights"] == editing_rights ? "user" : "eye")."\"></i>".pretty_binet_term($term["id"], false)."</div>",
            array("class" => "panel-list-element shadowed0","goto" => "true"));
        } ?>
      </div>
    </div>
  </div>
</div>
