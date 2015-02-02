<div class="show-container">
  <!-- TODO Etat du binet : arrière plan red- orange - green et icones
  check warning minus-circle ? -->
  <?php if(is_current_kessier()|| has_editing_rights($binet["id"],$binet["current_term"])){ ?>
  <div class="sh-plus green-background opanel">
    <i class="fa fa-fw fa-check"></i>
    <span class="text">Etat du binet</span>
  </div>
  <?php
  }?>
  <div class="sh-actions">
    <?php if(is_current_kessier()) {
       echo button("", "Changer de terme", "edit", "orange");
       }
    echo button(contact_binet_path($binet["id"]), "Contacter", "paper-plane", "grey");?>
  </div>
  <div class="sh-title opanel">
    <div class="logo">
      <i class="fa fa-5x fa-group"></i>
    </div>
    <div class="text">
      <span class="main"><?php
      echo pretty_binet($binet["id"]);
      if(is_current_kessier()|| has_editing_rights($binet["id"],$binet["current_term"])){
        echo link_to("#",
        "<i class=\"fa fa-fw fa-eye\"></i><span> Voir l'activité du binet </span>",
        array("class"=>"sh-bin-eye opanel0","title"=>"Voir l'activité"));
      }
       ?></span>
      <?php echo link_to("#","2013",array("class"=>"sub","title"=>"Voir une autre année"));?>
    </div>
  </div>
  <div class="sh-bin-admins opanel">
    <span class="title">
      Administrateurs
    </span>
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
    <?php if(is_current_kessier()){ ?>
      <div class="add">
        <?php echo button_small("","Ajouter un administrateur","plus","green",true);?>
        <?php echo button_small("","Supprimer un administrateur","minus","red",true);?>
      </div>
    <?php } ?>
  </div>
  <div class="sh-block-normal opanel">
    <?php echo $binet["description"];?>
  </div>
  <?php if(is_current_kessier() || has_editing_rights($binet["id"],$binet["current_term"])) {
    $real_income = 0;
    $income = 0;
    $real_spending = 0;
    $spending = 0;
    foreach($budgets as $budget){
      if($budget["amount"]>0){
        $real_income+=$budget["real_amount"];
        $income+=$budget["amount"];
      }
      else {
        $real_spending+=$budget["real_amount"];
        $spending+=$budget["amount"];
      }
    }
      ?>
  <div class="sh-bin-stats light-blue-background opanel">
    <div class="minipane" id="income">
      <div class="title">Recettes</div><?php echo pretty_amount($real_income);?>/
      <span><?php echo pretty_amount($income);?></span>
      </div>
    <div class="minipane" id="spending">
      <div class="title">Dépenses</div><?php echo pretty_amount($real_spending);?>/
      <span><?php echo pretty_amount($spending);?></span>
      </div>
    <div class="minipane" id="balance">
      <div class="title">Equilibre</div>
      <?php echo pretty_amount(sum_array($budgets,"real_amount")); ?> /
      <span><?php echo pretty_amount(sum_array($budgets,"amount")); ?></span>
    </div>
    <div class="minipane" id="subsidies_granted">
      <div class="title">Subventions accordées</div>
      <?php echo pretty_amount(sum_array($budgets,"subsidized_amount_granted")); ?>
      </div>
    <div class="minipane" id="subsidies_used">
      <div class="title">Subventions utilisées</div>
    <?php echo pretty_amount(sum_array($budgets,"subsidized_amount_used")); ?>
    </div>
  </div>
  <div class="sh-bin-resume light-blue-background opanel">
    <div class="title">
      Dépenses
    </div>
    <?php foreach($budgets as $budget){
      if($budget["amount"]<0){
      ?>
    <div class="line">
      <span class="label"><?php echo pretty_budget($budget["id"]);?></span>
      <span class="amount"><?php echo pretty_amount($budget["real_amount"]);?></span>
    </div>
    <?php
      }
    } ?>
  </div>
  <div class="sh-bin-resume light-blue-background opanel">
    <div class="title">
      Recettes
    </div>
    <?php foreach($budgets as $budget){
      if($budget["amount"]>0){
        ?>
        <div class="line">
          <span class="label"><?php echo pretty_budget($budget["id"]);?></span>
          <span class="amount"><?php echo pretty_amount($budget["real_amount"]);?></span>
        </div>
        <?php
      }
    } ?>
  </div>
  <?php
  }
  if(!empty($waves)) {
    ?>
  <div class="sh-bin-resume light-blue-background opanel">
    <div class="title">
      Vagues de subventions
    </div>
    <?php foreach($waves as $wave){
        ?>
        <div class="line">
          <span class="label"><?php echo pretty_wave($wave["id"]);?></span>
          <span class="submission date"><?php echo $wave["submission_date"];?></span>
            <span class="expiry date"> <?php echo $wave["expiry_date"];?></span>
        </div>
        <?php
    } ?>
  </div>
  <?php
    }
    ?>
</div>
