<script src = "<?php echo ASSET_PATH; ?>/js/show-budget.js"></script>
<div class="show-container">
  <div class="sh-title opanel">
    <div class="logo">
      <!-- TODO selon si c'est une dépense : fa-minus-circle ou une recette : fa-plus-circle -->
      <i class="fa fa-5x fa-plus-circle"></i>
    </div>
    <div class="text">
      <p class="main">
        <?php echo pretty_budget($budget["id"]); ?>
      </p>
      <p class="sub">
        <!-- TODO Nom du binet + mandat ex : Binet Raid 2014 -->
      </p>
    </div>
  </div>
  <div class="sh-bu-ratio opanel">
    <div class="header">
      Budget réel / prévisionnel
    </div>
    <div>
      <div class="used" id="real_budget">
      <!-- Ce script permet d'afficher une barre dont la longueur est proportionnelle au ratio
      budget réel (utilisé) / budget prévisionnel -->
      <script>
        spending = /*TODO  true ou false */ ;
        ratio1 = /* TODO ratio in [0,1] PHP -->*/;
        ratiobar(spending, ratio1 , 'real_budget');
      </script>
      <!-- TODO réel / prévisionnel -->
    </div>
  </div>
</div>
<div class="sh-bu-ratio opanel">
  <div class="header">
    Subventions utilisées / accordées
  </div>
  <div>
    <div class="used" id="subsidies">
    <script>
      ratio2 = /* TODO ratio in [0,1] PHP --> */;
      ratiobar( ratio2 , 'subsidies');
    </script>
    <!-- TODO utilisé / accordée ex : '549 / 1000'-->
    </div>
  </div>
</div>
<div class="sh-bu-tags opanel">
  <!-- TODO mettre les tags ici -->
</div>
</div>
