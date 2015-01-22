<?php

  function link_to($path, $caption, $class = "") {
    if (substr($path, 0, 4) != "http") {
      $path = "/".$path;
    }
    return "<a href=\"".$path."\"".(empty($class) ? "" : " class=\"".$class."\"").">".$caption."</a>";
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }
