<?php

  function link_to($path, $caption, $options = array()) {
    set_if_not_set($options["class"], "");
    set_if_not_set($options["id"], "");
    set_if_not_set($options["goto"], false);
    set_if_not_set($options["title"], false);

    if (!in_array(substr($path, 0, 7), array("mailto:", "http://")) && substr($path, 0, 1) != "#") {
      $path = "/".$path;
    }

    if (isset($GLOBALS["full_path_links"]) && $GLOBALS["full_path_links"]) {
      $path = full_path($path);
    }

    $parameters = empty($options["class"]) ? "" : " class=\"".$options["class"]."\"";
    $parameters .= empty($options["id"]) ? "" : " id=\"".$options["id"]."\"";
    $parameters .= empty($options["title"])? "" : " title=\"".$options["title"]."\"";

    if ($options["goto"]) {
      /* Sauts de ligne pour rendre le code soruce plus lisible */
      return preg_replace("/^(<[^>]*)(>)(.*)$/", "$1".$parameters." onclick=\"goto('".$path."')\">\n $3", str_replace("\n", "", $caption));
    } else {
      return "<a href=\"".$path."\"".$parameters.">".$caption."</a>";
    }
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }

  function button($path, $caption, $icon, $background_color, $link = true) {
    return button_template($path, $caption, $icon, $background_color, $link = true,"");
  }

  function button_small($path, $caption, $icon, $background_color, $link = true) {
    return button_template($path, $caption, $icon, $background_color, $link = true,"small");
}

function button_template($path, $caption, $icon, $background_color, $link = true,$size=""){
  if($size!=""){
    $size="-".$size;
  }
  $caption = "<div class=\"round-button".$size." ".$background_color."-background opanel\">
  <i class=\"fa fa-fw fa-".$icon.($link ? " anim" : "")."\"></i>
  <span>".$caption."</span>
  </div>";
  if ($link) {
    return link_to($path,$caption,array("goto" => true));
  } else {
    return $caption;
  }
}

function close_button($data_dismiss){
  return "<button type=\"button\" class=\"close\" data-dismiss=".$data_dismiss." aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
}

  function contact_binet_path($binet) {
    $path = "mailto:";
    foreach (select_current_admins($binet) as $admin) {
      $admin = select_student($admin["id"], array("name", "email"));
      $path .= $admin["name"]." <".$admin["email"].">, ";
    }
    return $path;
  }


  function month($date){
    return strftime("%B",strtotime($date));
  }

  function year($date){
    return strftime("%Y",strtotime($date));
  }
