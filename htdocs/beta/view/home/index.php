<div id="home-wrapper">
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
  <!-- Pour l'instant pas d'image pour le binet -->
  <?php foreach(binet_admins_current_student() as $binet_admin) {
    $binet_admin["binet_name"] = select_binet($binet_admin["binet"], array("name"))["name"];
    ?>
      <?php echo link_to(path("", "binet", binet_term_id($binet_admin["binet"], $binet_admin["term"])),
        "<div class=\"spot opanel\"><div class=\"binet-name\">".$binet_admin["binet_name"]."</div> <div class=\"binet-term\">".$binet_admin["term"]."</div></div>",
        array("goto"=>true)); ?>
    <?php
  }
  ?>
</div>
