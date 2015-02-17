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
        <?php foreach($binets as $binet){
          echo "<div class=\"panel-list-element opanel0\"><i class=\"icon fa fa-fw fa-group\"></i>".pretty_binet($binet["binet"])."</div>";
        } ?>
      </div>
    </div>
  </div>
</div>
