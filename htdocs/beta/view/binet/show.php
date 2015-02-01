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
      <span class="main"><?php echo pretty_binet($binet["id"]); ?></span>
      <?php echo link_to("#","2013",array("class"=>"sub","title"=>"Changer d'année"));?>
    </div>
  </div>
  <div class="sh-bin-admins opanel">
    <?php
    foreach (select_current_admins($binet["id"]) as $admin) {
      ?>
      <span class="admin">
        <i class="fa fa-fw fa-user logo"></i>
        <i class="fa fa-fw fa-send logo"></i>
        <?php echo pretty_student($admin["id"]); ?>
      </span>
      <?php
    }
    ?>
    <div class="add">
      <?php echo button("","Ajouter un administrateur","plus","green",true);?>
    </div>
  </div>
  <div class="sh-block-normal opanel">
    <?php echo $binet["description"];?>
  </div>
  <div class="sh-bin-resume opanel">
    <div class="title">
    </div>
    <ul class="spending">
      <?php foreach($budgets as $budget){
        if($budget["amount"]>0){
        ?>
      <li>
        <span><?php echo pretty_budget($budget["label"]);?></span>
        <i><?php echo pretty_amount($budget["amount"]);?></i>
      </li>
      <?php
        }
      } ?>
    </ul>
    <ul class="income">
      <?php foreach($budgets as $budget){
        if($budget["amount"]<0){
          ?>
          <li>
            <span><?php echo pretty_budget($budget["label"]);?></span>
            <i><?php echo pretty_amount($budget["amount"]);?></i>
          </li>
          <?php
        }
      } ?>
    </ul>
  </div>
</div>
