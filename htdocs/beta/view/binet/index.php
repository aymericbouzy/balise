<div id="public-index-wrapper">
  <div id="action-header" class="opanel2">
    <div id="action-title">Binets</div>
    <div class="searchbar">
      <?php echo fuzzy_input() ?>
    </div>
    <!-- Inutile pour l'instant'
    <div class="alpha-selecter">
      <!-- Ici utilisation d'ancres seulement : on peut garder des href="#ancre"-->
      <!-- TODO : voir comment implémenter ca -->
      <!-- <a href="#">A</a>
      <a href="#">B</a>
      <a href="#">C</a>
      <a href="#">D</a>
    </div> -->
  </div>
  <ul class="list">
    <?php for( $i=1 ; $i<=10 ;$i++){?>
    <li class="content-line-panel">
      <?php
      /* si kes besoin d'alerter sur l'état du binet */
      $binet_state_color="green";
      ob_start();
      echo "<div><i class=\"fa fa-3x fa-group\"></i>
      <span class=\"name\">Nom du binet ".$i." </span>";
      /* TODO : état du binet si KES*/
      echo "<span class=\"state ".$binet_state_color."-background\">Etat du binet</span>";
      /* TODO : couleur d'arrière plan des dates */
      /* On fait confiance à l'utilisateur pour reconnaitre date limite depot demande
      et limite utilisation ? */
      echo "<span class=\"users\">
      <span class=\"prez\">Jacques Lacan</span>
      <span class=\"trez\">Johhnie Walker</span>
      </span>
      <span class=\"amount ".$binet_state_color."-background\"> 200.00 </span>
      </div>";

      $content = ob_get_clean();

      echo link_to(path('',''), $content, array("class"=>"opanel clickable-main","goto"=>true));

      /* Here we put one "immediate" action depending on the user */
      ?>
      <span class="actions">
        <?php
        echo button(path("",""), "Contacter", "paper-plane", "grey");
        ?>
      </span>
    </li>
    <?php } ?>
  </ul>
</div>
<!-- Tout est ici pour le filtre sur les binets
Un helper avec les paramètres "container_name": ici 'public-index-wrapper'
                              "attribute_name":ici 'name'
serait utile ! -->
<?php echo fuzzy_load_scripts("public-index-wrapper","name"); ?>
