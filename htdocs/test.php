<?php
  include "global/initialisation.php";

  echo clean_string("coucou");
  echo "<br/>";
  echo clean_string("CoucOu");
  echo "<br/>";
  echo clean_string("coùcõu");
  echo "<br/>";
  echo clean_string("cou@ou");
