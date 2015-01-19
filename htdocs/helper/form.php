<?php

  function form_group($label, $field, $content, $object_name) {
    return "<div class=\"form-group".(in_array($field, $_SESSION[$object_name]["errors"]) ? " has-error" : "")."\">
              <label for=\"".$field."\">".$label."</label>
              ".$content."
            </div>";
  }

  function form_group_text($label, $field, $object, $object_name) {
    return form_group(
      $label,
      $field,
      "<input type=\"text\" class=\"form-control\" id=\"".$field."\" name=\"".$field."\" value=\"".($object[$field] ?: "")."\">",
      $object_name
    );
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

  function form_group_select($label, $field, $options, $object, $object_name) {
    $select_tag = "<select class=\"form-control\">";
    foreach ($options as $value => $option_label) {
      $select_tag .= "<option value=\"".$value."\"".($object[$field] == $value ? " selected=\"selected\"" : "").">".$option_label."</option>";
    }
    $select_tag .= "</select>";
    return form_group(
      $label,
      $field,
      $select_tag,
      $object_name
    );
  }

  function option_array($entries, $key_field, $value_field, $model_name) {
    $return_array = array();
    foreach ($entries as $entry) {
      $entry = call_user_func("select_".$model_name, $entry["id"], array($key_field, $value_field));
      $return_array[$entry[$key_field]] = $entry[$value_field];
    }
    return $return_array;
  }
