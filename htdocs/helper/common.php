<?php

  function link_to($path, $caption, $class = "") {
    return "<a href=\"".$path."\"".(empty($class) ? "" : " class=\"".$class."\"").">".$caption."</a>";
  }

  function img($src, $alt = "") {
    return "<img src=\"image/".$src."\" alt = \"".$alt."\"\>";
  }

  function form_group_text($label, $field, $object) {
    return "<div class=\"form-group\">
              <label for=\"".$field."\">".$label."</label>
              <input type=\"text\" class=\"form-control\" id=\"".$field."\" name=\"".$field."\" value=\"".($object[$field] ?: "")."\">
            </div>";
  }
