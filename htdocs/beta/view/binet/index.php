<div id="public-index-wrapper">
  <div id="action-header" class="opanel2">
    <div id="action-title">Binets</div>
    <div class="searchbar">
      <form class="form">
        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
        <input type="search" class="form-control pull-left">
      </form>
    </div>
    <div class="alpha-selecter">
      <!-- Ici utilisation d'ancres seulement : on peut garder des href="#ancre"-->
      <a href="#">A</a>
      <a href="#">B</a>
      <a href="#">C</a>
      <a href="#">D</a>
    </div>
  </div>
  <div class="content-line-panel">
    <?php
    /* si kes besoin d'alerter sur l'état du binet */
    $binet_state_color="green";
    ob_start();
    echo "<div><i class=\"fa fa-3x fa-money\"></i>
    <span class=\"name\">Nom du binet</span>";
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
    <span class=\"actions\">
      <?php
      echo button(path("",""), "Modifier", "edit", "grey");
      ?>
    </span>
  </div>

</div>
