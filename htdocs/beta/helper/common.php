<?php

  function link_to($path, $caption, $options = array()) {
    set_if_not_set($options["class"], "");
    set_if_not_set($options["id"], "");
    set_if_not_set($options["goto"], false);

    if (!in_array(substr($path, 0, 7), array("mailto:", "http://"))) {
      $path = "/".$path;
    }

    if (isset($GLOBALS["full_path_links"]) && $GLOBALS["full_path_links"]) {
      $path = full_path($path);
    }

    $parameters = empty($options["class"]) ? "" : " class=\"".$options["class"]."\"";
    $parameters .= empty($options["id"]) ? "" : " id=\"".$options["id"]."\"";

    if ($options["goto"]) {
      return preg_replace("/^(<[^>]*)(>)(.*)$/", "$1".$parameters." onclick=\"goto('".$path."')\">$3", str_replace("\n", "", $caption));
    } else {
      return "<a href=\"".$path."\"".$parameters.">".$caption."</a>";
    }
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }

  function button($path, $caption, $icon, $background_color, $link = true) {
    $caption = "<div class=\"round-button ".$background_color."-background opanel\">
                  <i class=\"fa fa-fw fa-".$icon.($link ? " anim" : "")."\"></i>
                  <span>".$caption."</span>
                </div>";
    if ($link) {
      return link_to(
        $path,
        $caption,
        array("goto" => true)
      );
    } else {
      return $caption;
    }
  }
