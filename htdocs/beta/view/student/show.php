<div class="sidebar-not-present">
  <div class="show-container">
    <div class="sh-title opanel">
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
    <div class="panel opanel light-blue-background">
      <div class="title">
        Binet administrés
      </div>
      <div class="content">
        <?php foreach(select_terms(array("student"=>$student["id"])) as $term){
          $term = select_term_binet($term["id"],array("binet"));
          echo link_to(path("show", "binet", $term["binet"]),
            "<div><i class=\"icon fa fa-fw fa-group\"></i>".pretty_binet($term["binet"],false)."</div>",
            array("class" => "panel-list-element opanel0","goto" => "true"));
        } ?>
      </div>
    </div>
  </div>
</div>
