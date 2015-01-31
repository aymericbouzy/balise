<div class="show-container">
  <!-- Etat du binet : arrière plan red- orange - green et icones
  check warning minus-circle ? -->
  <div class="sh-plus green-background opanel">
    <i class="fa fa-fw fa-check"></i>
    <span class="text">Etat du binet</span>
  </div>
  <div class="sh-title opanel">
    <div class="logo">
      <i class="fa fa-5x fa-group"></i>
    </div>
    <div class="text">
      <span class="main">Nom du binet</span>
      <?php echo link_to("#","2013",array("class"=>"sub","title"=>"Changer de terme"));?>
    </div>
  </div>
  <div class="sh-bin-admins opanel">
    <?php
    foreach (select_current_admins($binet["id"]) as $admin) {
      ?>
      <span class="admin">
        <i class="fa fa-fw fa-user">
        </i><?php echo pretty_student($admin["id"]); ?>
        <i class="fa fa-fw fa-send"></i>
      </span>
      <?php
    }
    ?>
    <div class="add">
      <?php echo button("","Ajouter un administrateur","plus","green",true);?>
    </div>
  </div>
</div>
