<?php

  function link_to($path, $caption, $options = array()) {
    set_if_not_set($options["goto"], false);

    if (!in_array(substr($path, 0, 7), array("mailto:", "http://")) && substr($path, 0, 1) != "#") {
      $path = "/".$path;
    }

    if (isset($GLOBALS["full_path_links"]) && $GLOBALS["full_path_links"]) {
      $path = full_path($path);
    }

    if ($options["goto"]) {
      $parameters = array_intersect_key($options, array_flip(array("class", "id", "title")));
      return insert_properties_in_html_tag(str_replace("\n", "", $caption), array_merge($parameters, array(
        "onclick" => "goto('".$path."')",
        "style" => "cursor:pointer"
      )));
    } else {
      $parameters = is_empty($options["class"]) ? "" : " class=\"".$options["class"]."\"";
      $parameters .= is_empty($options["id"]) ? "" : " id=\"".$options["id"]."\"";
      $parameters .= is_empty($options["title"])? "" : " title=\"".$options["title"]."\"";
      return "<a href=\"".$path."\"".$parameters.">".$caption."</a>";
    }
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }

  function button($path, $caption, $icon, $background_color, $link = true, $size = "",$label_position="right") {
    if ($size != "") {
      $size = "-".$size;
    }
    $caption = "<div class=\"round-button".$size." ".$background_color."-background opanel\">
    <i class=\"fa fa-fw fa-".$icon.($link ? " anim" : "")."\"></i>
    <span class=\"olabel ".$label_position."\">".$caption."</span>
    </div>";
    if ($link) {
      return link_to($path, $caption, array("goto" => true));
    } else {
      return $caption;
    }
  }

  function close_button($data_dismiss){
    return "<button type=\"button\" class=\"close\" data-dismiss=\"".$data_dismiss."\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
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
    $french_months = array(
      "01" => "Janvier",
      "02" => "Février",
      "03" => "Mars",
      "04" => "Avril",
      "05" => "Mai",
      "06" => "Juin",
      "07" => "Juillet",
      "08" => "Août",
      "09" => "Septembre",
      "10" => "Octobre",
      "11" => "Novembre",
      "12" => "Décembre"
    );
    return $french_months[strftime("%m",strtotime($date))];
  }

  function year($date){
    return strftime("%Y",strtotime($date));
  }

  function modal_toggle($id,$content,$class,$target){
    return "<span class=\"modal-toggle ".$class."\" id=\"".$id."\" data-toggle=\"modal\" data-target=\"#".$target."\">".$content."</span>";
  }

  function modal($id,$title,$content){
      return "<div class=\"modal fade\" id=\"".$id."\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">
                    ".close_button("modal")."
                    <h4 class=\"modal-title\">".$title."</h4>
                    </div>
                    <div class=\"modal-body\">
                      <div class=\"content\">\n".$content."</div>
                    </div>
                  </div>
                </div>
              </div>";
  }

  function text_tune_with_amount($amount,$text){
    return $amount." ".$text.($amount > 1 ? "s":"");
  }

  function insert_properties_in_html_tag($html_tag, $properties) {
    $properties_string = "";
    foreach ($properties as $property => $value) {
      $properties_string .= " ".$property."=\"".$value."\"";
    }
    return preg_replace("/^\s*(<[^>]*)(>)(.*)$/", "$1".$properties_string.">\n$3" , $html_tag);
  }
