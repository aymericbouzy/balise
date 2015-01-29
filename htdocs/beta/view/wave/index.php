<div id="public-index-wrapper">
  <div id="action-header" class="opanel2">
    <div id="action-title">Vagues de subventions</div>
    <div class="searchbar">
        <input type="search" class="fuzzy-search">
        <i class="fa fa-fw fa-search"></i>
    </div>
    <!-- Inutile pour le moment, le fuzzy finder suffit peut-être -->
    <!-- <div class="alpha-selecter">
      <a href="#">2015</a>
      <a href="#">2014</a>
      <a href="#">2013</a>
      <a href="#">2012</a>
    </div> -->
  </div>
  <ul class="list">
    <?php for( $i=1 ; $i<=10 ;$i++){?>
    <li class="content-line-panel">
      <?php ob_start();
        echo "<div><i class=\"fa fa-3x fa-money\"></i>
        <span class=\"name\">Nom de la subvention</span>";
        /* TODO : couleur d'arri�re plan de la subvention */
        echo "<span class=\"state orange-background\">Etat de la subvention</span>";
        /* TODO : couleur d'arri�re plan des dates */
        /* On fait confiance � l'utilisateur pour reconnaitre date limite depot demande
        et limite utilisation ? */
        echo "<span class=\"dates\">
          <span class=\"top green-background\">21/07/2015</span>
          <span class=\"bottom orange-background\">05/09/2015</span>
        </span>
        <span class=\"amount green-background\"> 200.00 </span>
        </div>";

        $content = ob_get_clean();

        echo link_to(path('',''), $content, array("class"=>"opanel clickable-main","goto"=>true));

        /* Here we put one "immediate" action depending on the user */
        ?>
        <span class="actions">
          <?php
          echo button(path("",""), "Demander des subventions", "question", "green");
        ?>
        </span>
      </li>
      <?php } ?>
  </ul>
</div>
<script src="<?php echo ASSET_PATH; ?>js/filter.js"></script>
<script src="<?php echo ASSET_PATH; ?>js/list.fuzzysearch.js"></script>
<script>
  var objects_list = new List('public-index-wrapper', {
    valueNames: ['name'],
    plugins: [ ListFuzzySearch() ]
  });
</script>
