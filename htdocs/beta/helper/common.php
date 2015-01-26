<?php

  function link_to($path, $caption, $options = array()) {
    set_if_not_set($options["class"], "");
    set_if_not_set($options["id"], "");
    set_if_not_set($options["goto"], false);

    if (!in_array(substr($path, 0, 7), array("mailto:", "http://"))) {
      $path = "/".$path;
    }

    $parameters = empty($options["class"]) ? "" : " class=\"".$options["class"]."\"";
    $parameters .= empty($options["id"]) ? "" : " id=\"".$options["id"]."\"";

    if ($options["goto"]) {
      return preg_replace("/^(<[^>]*)(>)(.*)$/", $parameters."$1 onclick=\"goto('".$path."')\">$3", str_replace("\n", "", $caption));
    } else {
      return "<a href=\"".$path."\"".$parameters.">".$caption."</a>";
    }
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }
