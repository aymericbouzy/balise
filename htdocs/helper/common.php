<?php

  function link_to($path, $caption, $options = array()) {
    set_if_not_set($options["goto"], false);

    if (!in_array(substr($path, 0, 7), array("mailto:", "http://", "https:/")) && substr($path, 0, 1) != "#") {
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

    } else if (!is_empty($options['modal'])) {
    	set_if_not_set($options['modal']['title'], "");
    	// The message should be set if the 'modal' options is used, but we provide a default one
    	set_if_not_set($options['modal']['message'], " Es-tu sûr de vouloir faire cela ?");
    	// A modal toggle should at least be a button and not only a text in a div
    	set_if_not_set($options['modal']['class'], "btn");

    	$modal_id = is_empty($options['modal']["id"]) ? $options["id"]."_modal_auto_id" : $options['modal']["id"] ;
    	$button_in_modal = link_to($path,"<div> Confirmer </div>",array("class" => "btn"));
    	$content = $options['modal']['message']."<div class=\"button-container\">".$button_in_modal."</div>";
    	$modal = modal($modal_id, array("title" => $options['modal']["title"]), $content);

    	return modal_toggle($options["id"], $caption,
    			$options["class"], $modal_id)."\n".$modal;

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
    $caption = "<div class=\"round-button".$size." ".$background_color."-background shadowed\">
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

  function modal($id, $content, $options = array()) {
    return "<div class=\"modal fade\" id=\"".$id."\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">
              <div class=\"modal-dialog\">
                <div class=\"modal-content\">".
                  (!is_empty($options["title"]) ?
                    "<div class=\"modal-header\">
                      ".close_button("modal")."
                      <h4 class=\"modal-title\">".$options['title']."</h4>
                    </div>" :
                  "" ).
                  "<div class=\"modal-body\">".$content."</div>
                </div>
              </div>
            </div>";
  }

  function tip($content){
    return "<span class=\"tip\"><i class=\"fa fa-fw fa-info-circle\"></i> ".$content."</span>";
  }
  function info_tooltip($content){
    return insert_tooltip("<i style=\"cursor:pointer\" class=\"fa fa-fw fa-info-circle\"></i>", $content);
  }

  function text_tune_with_amount($amount,$text){
    return $amount." ".$text.($amount > 1 ? "s":"");
  }

  function insert_properties_in_html_tag($html_tag, $properties) {
    $html_tag = preg_replace('/\s+/', ' ', trim($html_tag));
    $properties_string = "";
    foreach ($properties as $property => $value) {
      $properties_string .= " ".$property."=\"".$value."\"";
    }
    return preg_replace("/^(\s*<[^>]*)(>)(.*)$/", "$1".$properties_string.">\n$3", $html_tag);
  }

  function list_to_human_string($list, $pretty_printer) {
    $human_string = "";
    $index = 0;
    foreach ($list as $object) {
      $human_string = ($index == 1 ? " et " : (($index > 1) ? ", " : "")) . $human_string;
      $human_string = call_user_func($pretty_printer, $object) . $human_string;
      $index += 1;
    }
    return $human_string;
  }

  function get_debug_context() {
    $url = $_SERVER["REQUEST_URI"];
    $browser = $_SERVER["HTTP_USER_AGENT"];
    $email = connected_student() ? select_student($_SESSION["student"], array("email"))["email"] : "";
    $post = array_to_string($_POST);
    $session = array_to_string($_SESSION);
    $get = array_to_string($_GET);
    return "\nURL demandée :\t\t\t\t\t".$url."\nBrowser :\t\t\t\t\t\t\t".$browser."\npersonne connectée :\t\t\t\t".$email."\nétat de la variable \$_POST :\t\t\t".$post."\nétat de la variable \$_SESSION :\t\t".$session."\nétat de la variable \$_GET :\t\t\t".$get;
  }

  function get_bug_reference() {
    return "bug #".substr(md5(rand()), 0, 10);
  }

  function badged_counter($counter) {
    if ($counter > 0) {
      return " <span class=\"badge\">".$counter."</span>";
    } else {
      return "";
    }
  }
