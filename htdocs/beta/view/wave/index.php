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
  <?php echo link_to(path('',''),
    "<div><i class=\"fa fa-3x fa-money\"></i>
      <span class=\"name\">Nom de la subvention</span>
      <span class=\"state orange-background\">Etat de la subvention</span>
      <span class=\"dates\">
        <span class=\"top green-background\">21/07/2015</span>
        <span class=\"bottom orange-background\">05/09/2015</span>
      </span>
      <span class=\"amount green-background\"> 200.00 </span>
      </div>",
      array("class"=>"opanel content-line-panel","goto"=>true));
      ?>

</div>
