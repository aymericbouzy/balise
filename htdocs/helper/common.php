<?php

  function link_to($path, $caption, $class = "") {
    if (!in_array(substr($path, 0, 7), array("mailto:", "http://"))) {
      $path = "/".$path;
    }
    return "<a href=\"".$path."\"".(empty($class) ? "" : " class=\"".$class."\"").">".$caption."</a>";
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }

  function button($path, $caption, $icon, $background_color) {
    return link_to(
      $path,
      "<div class=\"round-button ".$background_color."-background opanel\">
        <i class=\"fa fa-fw fa-".$icon." anim\"></i>
        <span>".$caption."</span>
      </div>",
      array("goto" => true)
    );
  }
