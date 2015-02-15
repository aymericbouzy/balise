<?php

  function create_form($form_name) {
    include FORM_PATH.$form_name.".php";
    $GLOBALS[$form_name."_form"] = $form;
  }

  function check_form($form_name) {
    // get form
    $form = $GLOBALS[$form_name."_form"];
    // check for presence of input
    $sanitized_input = sanitize_input($form);
    // save input in case of error
    $_SESSION[$form_name."_form"] = $sanitized_input;
    // put input to the right format for treatment
    $formatted_input = format_input_forward($sanitized_input, $form);
    // validate input correctness; redirects if not valid
    validate_formatted_input($formatted_input, $form);
    // unset now useless session variable
    unset($_SESSION[$form_name."_form"]);
    // return input nicely structured
    return structured_input($formatted_input, $form);
  }

  function sanitize_input($form) {
    $sanitized_input = array();
    foreach ($form["fields"] as $name => $field) {
      if (!isset($_POST[$name]) && !$field["optionnal"]) {
        $_SESSION["error"][] = "Tu n'as pas rempli ".$field["human_name"].".";
        $_SESSION[$form["name"]."_errors"][] = $name;
        $sanitized_input[$name] = default_value_for_type($name);
      } else {
        $sanitized_input[$name] = $_POST[$name];
      }
    }
    return $sanitized_input;
  }

  function format_input_forward($sanitized_input, $form) {
    $translated_input = $sanitized_input;
    foreach ($sanitized_input as $name => $value) {
      $field = $form["fields"][$name];
      $valid = false;
      switch ($field["type"]) {
        case "amount":
        if (is_numeric($value)) {
          $translated_input[$name] = floor($value * 100);
          $valid = true;
        }
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
        case "boolean":
        $valid = in_array($value, array(0, 1));
        if (!$valid) {
          $sanitized_input = default_value_for_type("boolean");
        }
        break;
        case "id":
        $valid = is_numeric($value);
        if (!$valid) {
          $sanitized_input = default_value_for_type("id");
        }
        break;
      }
      if (!$valid) {
        $_SESSION["error"][] = ucfirst($field["human_name"])." n'est pas au bon format";
        $_SESSION[$form["name"]."_errors"][] = $name;
      }
    }
    return $sanitized_input;
  }

  function validate_formatted_input($input, $form) {
    foreach ($input as $name => $value) {
      $field = $form["fields"][$name];
      switch ($field["type"]) {
        case "id":
        if (!call_user_func("exists_".$field["model"], $value)) {
          $_SESSION[$form["name"]."_errors"][] = $name;
        }
        break;
        case "amount":
        if ($value < 0 || $value > MAX_AMOUNT) {
          $_SESSION[$form["name"]."_errors"][] = $name;
          $_SESSION["error"][] = ucfirst($field["human_name"])." doit être compris entre 0 et ".(MAX_AMOUNT / 100).".";
        }
        break;
        case "text":
        if (strlen($value) > MAX_TEXT_LENGTH) {
          $_SESSION[$form["name"]."_errors"][] = $name;
          $_SESSION["error"][] = ucfirst($field["human_name"])." ne peut pas avoir plus de ".MAX_TEXT_LENGTH." caractères.";
        }
        break;
        case "name":
        if (strlen($value) > MAX_NAME_LENGTH) {
          $_SESSION[$form["name"]."_errors"][] = $name;
          $_SESSION["error"][] = ucfirst($field["human_name"])." ne peut pas avoir plus de ".MAX_NAME_LENGTH." caractères.";
        }
      }
    }
    foreach ($form["validations"] as $validation) {
      call_user_func($validation, $input);
    }
    if (!is_empty($_SESSION["error"]) || !is_empty($_SESSION[$form["name"]."_errors"])) {
      redirect_to_path($form["redirect_to_if_error"]);
    }
  }

  function structured_input($validated_input, $form) {
    if (isset($form["structured_input_maker"])) {
      return call_user_func($form["structured_input_maker"], $validated_input);
    } else {
      return $validated_input;
    }
  }

  function default_value_for_type($type) {
    $default_value = array(
      "amount" => 0,
      "date" => "",
      "id" => 1,
      "boolean" => 0,
      "text" => "",
      "name" => ""
    );
    return $default_value[$type];
  }

  function get_html_form($form_name) {
    $form = $GLOBALS[$form_name."_form"];
    if (!is_empty($_SESSION[$form_name."_form"])) {
      $GLOBALS["prefill_form_values"] = $_SESSION[$form_name."_form"];
      unset($_SESSION[$form_name."_form"]);
    } else {
      $GLOBALS["prefill_form_values"] = call_user_func($form["initialise_form"]);
    }
    ob_start();
    ?>
    <form role="form" id="<?php echo $form["name"]; ?>" action="/<?php echo $form["destination_path"]; ?>" method="post">
      <?php echo form_csrf_token(); ?>
      <?php include $form["html_form_path"]; ?>
    </form>
    <?php
    return ob_get_clean();
  }
