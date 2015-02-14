<?php

  function create_form($form_name) {
    include FORM_PATH.$form_name.".php";
    $GLOBALS[$form_name."_form"] = $form;
  }

  function check_form($form_name) {
    $sanitized_input = sanitize_input($form_name);
    $_SESSION[$form_name."_form"] = $sanitized_input;
    $translated_input = translate_input_forward($sanitized_input, $form_name);
    validate_input($translated_input, $form_name);
    return structured_input($translated_input, $form_name);
  }

  function sanitize_input($form_name) {
    $form = $GLOBALS[$form_name."_form"];
    $sanitized_input = array();
    foreach ($form["fields"] as $name => $field) {
      if (!isset($_POST[$name]) && !$field["optionnal"]) {
        $_SESSION["error"][] = "Tu n'as pas rempli ".$field["human_name"].".";
        $_SESSION[$form_name."_errors"][] = $name;
        $sanitized_input[$name] = default_value_for_type($name);
      } else {
        $sanitized_input[$name] = $_POST[$name];
      }
    }
    return $sanitized_input;
  }

  function translate_input_forward($sanitized_input, $form_name) {
    $form = $GLOBALS[$form_name."_form"];
    $translated_input = $sanitized_input;
    foreach ($sanitized_input as $name => $value) {
      $field = $form["fields"][$name];
      $valid = false;
      switch ($field["type"]) {
        case "amount":
        $translated_input[$name] = $value / 100;
        $valid = true;
        break;
        case "date";
        $regex = "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/";
        $matched_groups = array();
        if (preg_match($regex, $value, $matched_groups)) {
          $days_in_month = array(
            "01" => "31",
            "02" => $matched_groups[3] % 4 == 0 && $matched_groups[3] % 100 != 0 ? "29" : "28",
            "03" => "31",
            "04" => "30",
            "05" => "31",
            "06" => "30",
            "07" => "31",
            "08" => "31",
            "09" => "30",
            "10" => "31",
            "11" => "30",
            "12" => "31"
          );
          if ($matched_groups[1] > "00" && $matched_groups[1] <= $days_in_month[$matched_groups[2]]) {
            $translated_input[$name] = $matched_groups[3]."-".$matched_groups[2]."-".$matched_groups[1];
            $valid = true;
          }
        }
        break;
      }
      if (!$valid) {
        $_SESSION["error"][] = ucfirst($field["human_name"])." n'est pas au bon format".
      }
    }
    return $sanitized_input;
  }

  function validate_input($input, $form_name) {
    $form = $GLOBALS[$form_name."_form"];
    foreach ($input as $name => $value) {
      $field = $form["fields"][$name];
      switch ($field["type"]) {
        case "boolean":


      }
    }
  }
