<?php
  include "global/initialisation.php";

  echo (link_to(path("login", "home"), "<p>
  login
  </p>", array("id" => "fake", "class" => "fake")));
  echo (link_to(path("login", "home"), "<p>
  login
  </p>", array("goto" => true)));
  echo preg_replace("/^(<[^>]*)(>)(.*)$/", "$1 onclick=\"goto('/".$path."')\">$3", str_replace("\n", "", "<p>
    login
  </p>"));
  var_dump(preg_does_match("/^(<[^>]*)(>)(.*)$/", str_replace("\n", "", "<p>
  login
  </p>")));
