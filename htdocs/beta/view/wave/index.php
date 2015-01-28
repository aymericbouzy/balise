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
    "<i class=\"fa fa-3x fa-money\"></i>
      <span class=\"name\">Nom du binet</span>",
      array("class"=>"opanel content-line-panel"));
      ?>

</div>
