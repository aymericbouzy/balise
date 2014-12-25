<?php

  function link_to($path, $caption, $class = "") {
    return "<a href=\"/".$path."\"".(empty($class) ? "" : " class=\"".$class."\"").">".$caption."</a>";
  }

  function img($src, $alt = "") {
    return "<img src=\"".IMG_PATH.$src."\" alt = \"".$alt."\"\>";
  }

  function form_group_text($label, $field, $object, $object_name) {
    return "<div class=\"form-group".(in_array($field, $_SESSION[$object_name]["errors"]) ? " has-error" : "")."\">
              <label for=\"".$field."\">".$label."</label>
              <input type=\"text\" class=\"form-control\" id=\"".$field."\" name=\"".$field."\" value=\"".($object[$field] ?: "")."\">
            </div>";
  }

  function form_csrf_token() {
    return "<input type=\"hidden\" name=\"csrf_token\" value=\"".get_csrf_token()."\">";
  }

  function form_group_checkbox($label, $field, $object, $object_name) {
    return "<div class=\"checkbox".(in_array($field, $_SESSION[$object_name]["errors"]) ? " has-error" : "")."\">
              <label>
                <input type=\"hidden\" name=\"".$field."\" value=\"0\">
                <input type=\"checkbox\" id=\"".$field."\" name=\"".$field."\" value=\"1\"".(empty($object[$field]) ? "" : " checked").">
                ".$label."
              </label>
            </div>";
  }

  function form_submit_button($label) {
    return "<input type=\"submit\" class=\"btn btn-default\" value=\"".$label."\">";
  }
