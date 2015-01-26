<<<<<<< HEAD
<div class="home-wrapper">
=======
<div id="home-wrapper">
>>>>>>> 78e8733fc2b1d6b76bfb37c058a6390eac90a46a
  <!-- TODO : si new, les mettre dans le flashcard news -->
  <div class="flashcard opanel news alert alert-dismissible fade in">
    <button class="close" data-dismiss="alert">
      <i class="fa fa-fw fa-close"></i>
    </button>
    <h1><!-- TODO : mettre le titre de l'info ici --></h1>
    <p>
      <!-- TODO mettre le message de l'info ici -->
    </p>
    <div class="controls">
      <!-- TODO : ici des exemples de contrôles -- WIP : encore maldéfini, et peut $etre pas nécéssaire -->
      <span class="ctrl"><i class="fa fa-fw fa-cog bt"></i><span class="which-action">Paramètres</span></span>
      <span class="ctrl"><i class="fa fa-fw fa-check bt"></i><span class="which-action">Valider</span></span>
    </div>
  </div>
  <!--TODO Pour chaque binet un 'spot' -->
  <!-- TODO si pas de binet afficher un message sympa : vous n'avez pas de binet -->
  <div class="spot opanel">
    <?php
      $binet_has_image=false;
      if($binet_has_image){
        ?>
        <!-- TODO : metre l'image du binet : elle doit être carrée -->
        <img src="imagedubinet-carrée">
    <?php
    }
    else {
    ?>
      <img src="<?php echo ASSET_PATH; ?>img/binet.png">
      <?php
    }
    ?>
  </div>
</div>
