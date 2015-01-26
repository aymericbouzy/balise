<?php
  include "global/initialisation.php";

  var_dump(link_to(path("login","home"), "<div>Connexion via Frankiz</div>", array("class" => "opanel", "id" => "login", "goto"=>true)));
