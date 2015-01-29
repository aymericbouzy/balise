<div id="public-index-wrapper">
  <div id="action-header" class="opanel2">
    <div id="action-title">Vagues de subventions</div>
    <div class="searchbar">
      <form class="form">
        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
        <input type="search" class="form-control pull-left">
      </form>
    </div>
    <div class="alpha-selecter">
      <a href="#">2015</a>
      <a href="#">2014</a>
      <a href="#">2013</a>
      <a href="#">2012</a>
    </div>
  </div>
  <div class="content-line-panel">
    <?php ob_start();
      echo "<div><i class=\"fa fa-3x fa-money\"></i>
      <span class=\"name\">Nom de la subvention</span>";
      /* TODO : couleur d'arrière plan de la subvention */
      echo "<span class=\"state orange-background\">Etat de la subvention</span>";
      /* TODO : couleur d'arrière plan des dates */
      /* On fait confiance à l'utilisateur pour reconnaitre date limite depot demande
      et limite utilisation ? */
      echo "<span class=\"dates\">
        <span class=\"top green-background\">21/07/2015</span>
        <span class=\"bottom orange-background\">05/09/2015</span>
      </span>
      <span class=\"amount green-background\"> 200.00 </span>
      </div>";

      $content = ob_get_clean();

      echo link_to(path('',''), $content, array("class"=>"opanel clickable-main","goto"=>true));

      ob_start();
      /* Here we put one "immediate" action depending on the user */
      echo "<span class=\"actions\">
              <div class=\"round-button anim opanel red-background\">
                  <i class=\"fa fa-fw fa-automobile\"></i>
                  <span>Action</span>
              </div>
      </span>";

      $actions = ob_get_clean();

      echo link_to(path('',''), $actions, array("class"=>"opanel content-line-panel","goto"=>true));
      ?>
    </div>
</div>
