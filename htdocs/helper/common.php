<?php

  function link_to($path, $caption, $class = "") {
    return "<a href=\"".$path."\"".(empty($class) ? "" : " class=\"".$class."\"").">".$caption."</a>";
  }

  function img($src, $alt = "") {
    return "<img src=\"image/".$src."\" alt = \"".$alt."\"\>";
  }
