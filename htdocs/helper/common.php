<?php

  function link_to($path, $caption, $class = "") {
    return "<a href=\"/".$path."\"".(empty($class) ? "" : " class=\"".$class."\"").">".$caption."</a>";
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }

  function form_group_text($label, $field, $object) {
    return "<div class=\"form-group\">
              <label for=\"".$field."\">".$label."</label>
              <input type=\"text\" class=\"form-control\" id=\"".$field."\" name=\"".$field."\" value=\"".($object[$field] ?: "")."\">
            </div>";
  }

  function form_csrf_token() {
    return "<input type=\"hidden\" name=\"csrf_token\" value=\"".get_csrf_token()."\">";
  }

  function form_group_checkbox($label, $field, $object) {
    return "<div class=\"checkbox\">
              <label>
                <input type=\"checkbox\" id=\"".$field."\" name=\"".$field."\" value=\"true\"".(empty($object[$field]) ? "" : " checked").">
                ".$label."
              </label>
            </div>";
  }

  function form_submit_button($label) {
    return "<div type=\"submit\" class=\"btn btn-default\">".$label."</div>";
  }
