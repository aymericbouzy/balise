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
  <?php echo link_to(path('',''),
  "<div><i class=\"fa fa-3x fa-money\"></i>
  <span class=\"name\">Nom du binet</span>
  <span class=\"amount red-background\"> -200.00 </span>
  </div>",
  array("class"=>"opanel content-line-panel","goto"=>true));
  ?>

</div>
