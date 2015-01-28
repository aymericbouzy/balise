<?php
  include "global/initialisation.php";

  var_dump(link_to(
  path("",""),
  "<div>   <i class=\"fa-fw fa fa-money \"></i>  Liste des subventions </div>",
  array("class" => "homelink", "id" => "subsidies","goto" => "true" )));
