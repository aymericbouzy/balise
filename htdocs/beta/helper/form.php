<?php

  function form_input($label, $field_name, $form, $parameters = array()) {
    set_if_not_set($parameters["html_decoration"], array());
    $field = $form["fields"][$field_name];
    switch ($field["type"]) {
      case "amount":
      return form_group_text($label, $field_name, $field["value"] / 100, $form["name"], $parameters["html_decoration"]);
      case "id":
      if (is_empty($form_field["hidden"])) {
        return form_group_select($label, $field_name, option_array($field["options"], $field["key"], $field["label"], $field["model"]), $field["value"], $form["name"]);
      } else {
        return form_hidden($field_name, $field["value"]);
      }
      case "date":
      return form_group_date($label, $field_name, $field["value"], $form["name"]);
      case "boolean":
      switch ($field["selection_method"]) {
        case "radio":
        return form_group_radio($field_name, $label, $field["value"], $form["name"]);
        default:
        return form_group_checkbox($label, $field_name, $field["value"], $form["name"]);
      }
      case "name":
      return form_group_text($label, $field_name, $field["value"], $form["name"], $parameters["html_decoration"]);
      case "text":
      return form_group_textarea($label, $field_name, $field["value"], $form["name"], $parameters["html_decoration"]);
    }
  }

  function form_group($label, $field, $content, $form_name) {
    return "<div class=\"form-group".(isset($_SESSION[$form_name."_errors"]) && in_array($field, $_SESSION[$form_name."_errors"]) ? " has-error" : "")."\">
              <label for=\"".$field."\">".$label."</label>
              ".$content."
            </div>";
  }

  function form_group_text($label, $field, $prefill_value, $form_name, $html_decoration = array()) {
    set_if_not_set($html_decoration["class"], "");
    $html_decoration["class"] .= " form-control";
    $html_decoration_string = "";
    foreach ($html_decoration as $property => $value) {
      $html_decoration_string .= " ".$property."=\"".$value."\"";
    }
    return form_group(
      $label,
      $field,
      "<input type=\"text\"".$html_decoration_string." id=\"".$field."\" name=\"".$field."\" value=\""
      .$prefill_value."\">",
      $form_name
    );
  }

  function form_group_textarea($label, $field, $prefill_value, $form_name, $html_decoration = array()) {
    set_if_not_set($html_decoration["class"], "");
    $html_decoration["class"] .= " form-control";
    $html_decoration_string = "";
    foreach ($html_decoration as $property => $value) {
      $html_decoration_string .= " ".$property."=\"".$value."\"";
    }
    return form_group(
      $label,
      $field,
      "<textarea ".$html_decoration_string." id=\"".$field."\" name=\"".$field."\">".$prefill_value."</textarea>",
      $form_name
    );
  }

  function form_group_date($label, $field, $prefill_value, $form_name) {
    $regex = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
    if (preg_does_match($regex, $prefill_value)) {
      $prefill_value = preg_replace($regex, "$3/$2/$1", $prefill_value);
    }
    return form_group(
      $label,
      $field,
      "<input type=\"text\" class=\"form-control\" id=\"".$field."\" name=\"".$field."\" value=\"".$prefill_value."\">
      <script type=\"text/javascript\">
      $(function () {
        $('#".$field."').datetimepicker({
          format: 'DD/MM/YYYY'
        });
      });
      </script>",
      $form_name
    );
  }

  function form_csrf_token() {
    return form_hidden("csrf_token", get_csrf_token());
  }

  function form_hidden($field, $value) {
    return "<input type=\"hidden\" name=\"".$field."\" value=\"".$value."\">";
  }

  function form_group_checkbox($label, $field, $prefill_value, $form_name) {
    return "<div class=\"checkbox".(isset($_SESSION[$form_name."_errors"]) && in_array($field, $_SESSION[$form_name."_errors"]) ? " has-error" : "")."\">
              <label>
                <input type=\"hidden\" name=\"".$field."\" value=\"0\">
                <input type=\"checkbox\" id=\"".$field."\" name=\"".$field."\" value=\"1\"".(is_empty($prefill_value) ? "" : " checked").">
                ".$label."
              </label>
            </div>";
  }

  function form_submit_button($label) {
    return "<input type=\"submit\" class=\"btn btn-default\" value=\"".$label."\">";
  }

  function form_group_select($label, $field, $options, $prefill_value, $form_name) {
    $select_tag = "<select class=\"form-control\" id=\"".$field."\" name=\"".$field."\">";
    foreach ($options as $value => $option_label) {
      $select_tag .= "<option value=\"".$value."\"".($prefill_value == $value ? " selected=\"selected\"" : "").">".$option_label."</option>";
    }
    $select_tag .= "</select>";
    return form_group(
      $label,
      $field,
      $select_tag,
      $form_name
    );
  }

  function form_group_radio($field, $options, $prefill_value, $form_name) {
    $form_group_radio = "";
    $check_first = $prefill_value == "";
    foreach ($options as $value => $label) {
      $form_group_radio .= "<div class=\"radio".(isset($_SESSION[$form_name."_errors"]) && in_array($field, $_SESSION[$form_name."_errors"]) ? " has-error" : "")."\">
        <label>
          <input type=\"radio\" name=\"".$field."\" id=\"".$field.$value."\" value=\"".$value."\"".($prefill_value == $value || $include_first ? " checked" : "").">
          ".$label."
        </label>
      </div>";
      $check_first = false;
    }
    return $form_group_radio;
  }

  function option_array($entries, $key_field, $value_field, $model_name) {
    $return_array = array();
    foreach ($entries as $entry) {
      $entry = call_user_func("select_".$model_name, $entry["id"], array($key_field, $value_field));
      $return_array[$entry[$key_field]] = $entry[$value_field];
    }
    return $return_array;
  }

  function paid_by_to_caption($paid_by) {
    if ($paid_by > 0) {
      return pretty_student($paid_by, false);
    } else {
      $other_options = paid_by_static_options();
      return $other_options[$paid_by];
    }
  }

  function exists_paid_by($paid_by) {
    return in_array($paid_by, array_keys(paid_by_static_options())) || exists_student($paid_by);
  }

  function paid_by_static_options() {
    return array(
      "0" => "",
      "-1" => "Virement Kès",
      "-2" => "Virement Corps",
      "-3" => "Virement DFS"
    );
  }
