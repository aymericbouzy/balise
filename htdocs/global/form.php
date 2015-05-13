<?php

  function create_form($form_name) {
    $form = array();
    $form["name"] = $form_name;
    include FORM_PATH.$form_name.".php";
    $form["fields"]["submit"] = create_name_field("Le bouton pour soumettre le formulaire");
    $GLOBALS[$form_name."_form"] = $form;
  }

  function check_form($form_name) {
    // get form
    $form = $GLOBALS[$form_name."_form"];
    // check for presence of input
    $sanitized_input = sanitize_input($form);
    // put input to the right format for treatment
    $formatted_input = format_input_forward($sanitized_input, $form);
    // save input in case of error
    $_SESSION[$form_name."_form"] = $formatted_input;
    // validate input correctness; redirects if not valid
    validate_formatted_input($formatted_input, $form);
    // unset now useless session variable
    unset($_SESSION[$form_name."_form"]);
    // replace $_POST variable with input nicely structured
    $_POST = structured_input($formatted_input, $form);
  }

  function sanitize_input($form) {
    $sanitized_input = array();
    foreach ($form["fields"] as $name => $field) {
      if (is_empty($field["disabled"])) {
        if ((!isset($_POST[$name]) || (is_empty($_POST[$name]) && !in_array($field["type"], array("boolean", "id")))) && is_empty($field["optional"])) {
          add_form_error($form["name"], $name, "Tu n'as pas rempli ".$field["human_name"].".");
          $sanitized_input[$name] = default_value_for_type($field["type"]);
        } else {
          if (is_empty($_POST[$name]) && $field["type"] == "id" && !is_empty($field["multiple"])) {
            $sanitized_input[$name] = array();
          } else {
            $sanitized_input[$name] = $_POST[$name];
          }
        }
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
          $translated_input[$name] = round($value * 100);
          $valid = true;
        } elseif (is_empty($value)) {
          $valid = true;
        }
        break;
        case "date";
        $regex = "/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/";
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
        if ($value == "") {
          $valid = true;
        }
        break;
        case "boolean":
        $valid = in_array($value, array(0, 1));
        if (!$valid) {
          $translated_input[$name] = default_value_for_type("boolean");
        }
        break;
        case "id":
        if (is_empty($field["multiple"])) {
          $value = array($value);
        }
        $all_values = array();
        $valid = true;
        foreach ($value as $id_value) {
          if (!is_numeric($id_value)) {
            $valid = false;
          } else {
            $all_values[] = $id_value;
          }
        }
        $translated_input[$name] = array_unique($all_values);
        break;
        case "text":
        case "name":
        $translated_input[$name] = nl2br(htmlspecialchars($sanitized_input[$name], ENT_IGNORE));
        $valid = true;
        break;
        default:
        $valid = true;
        break;
      }
      if (!$valid) {
        add_form_error($form["name"], $name, ucfirst($field["human_name"])." n'est pas au bon format.");
      }
    }
    return $translated_input;
  }

  function validate_formatted_input($input, $form) {
    $no_format_error = is_empty($_SESSION[$form["name"]."_errors"]);
    foreach ($input as $name => $value) {
      $field = $form["fields"][$name];
      switch ($field["type"]) {
        case "id":
        foreach ($value as $id_value) {
          if (!call_user_func("exists_".$field["model"], $id_value)) {
            add_form_error($form["name"], $name);
          }
        }
        break;
        case "amount":
        if ($value < $field["min"]) {
          add_form_error($form["name"], $name, ucfirst($field["human_name"])." doit être supérieur à ".pretty_amount($field["min"], false).".");
        }
        if ($value > $field["max"]) {
          add_form_error($form["name"], $name, ucfirst($field["human_name"])." doit être inférieur à ".pretty_amount($field["max"], false).".");
        }
        break;
        case "text":
        if (strlen($value) > $field["max"]) {
          add_form_error($form["name"], $name, ucfirst($field["human_name"])." ne peut pas avoir plus de ".$field["max"]." caractères.");
        }
        break;
        case "name":
        if (strlen($value) > $field["max"]) {
          add_form_error($form["name"], $name, ucfirst($field["human_name"])." ne peut pas avoir plus de ".$field["max"]." caractères.");
        }
        break;
        case "date":
        if (isset($field["min"]) && $value < $field["min"]) {
          add_form_error($form["name"], $name, ucfirst($field["human_name"])." doit être après le ".pretty_date($field["min"]).".");
        }
        if (isset($field["max"]) && $value > $field["max"]) {
          add_form_error($form["name"], $name, ucfirst($field["human_name"])." doit être avant le ".pretty_date($field["max"]).".");
        }
        break;
      }
    }
    if (isset($form["validations"]) && ($no_format_error || !is_empty($form["ignore_format_errors_for_validation"]))) {
      foreach ($form["validations"] as $validation) {
        add_form_error($form["name"], "", call_user_func($validation, $input));
      }
    }
    if (!isset($_POST["csrf_token"]) || !valid_csrf_token($_POST["csrf_token"])) {
      $_SESSION["error"][] = "Une erreur s'est produite. Tu peux réessayer de soumettre le formulaire.";
    }
    if (!is_empty($_SESSION["error"]) || !is_empty($_SESSION[$form["name"]."_errors"])) {
      redirect_to_path($form["redirect_to_if_error"]);
    }
  }

  function add_form_error($form_name, $field_name, $message = "") {
    if (!is_empty($field_name)) {
      $_SESSION[$form_name."_errors"][] = $field_name;
    }
    if (!is_empty($message)) {
      $_SESSION["error"][] = $message;
    }
  }

  function structured_input($validated_input, $form) {
    foreach ($validated_input as $name => $value) {
      $field = $form["fields"][$name];
      switch ($field["type"]) {
        case "id":
        if (is_empty($field["multiple"])) {
          $validated_input[$name] = is_empty($value) ? default_value_for_type("id") : $value[0];
        }
        break;
      }
    }
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
    $GLOBALS["form"] = $form;
    if (!is_empty($_SESSION[$form_name."_form"])) {
      $prefill_form_values = $_SESSION[$form_name."_form"];
      unset($_SESSION[$form_name."_form"]);
    } elseif (isset($form["initialise_form"])) {
      $prefill_form_values = call_user_func($form["initialise_form"]);
      foreach ($form["fields"] as $name => $field) {
        if ($field["type"] == "id" && is_empty($field["multiple"]) && isset($prefill_form_values[$name])) {
          $prefill_form_values[$name] = array($prefill_form_values[$name]);
        }
      }
    } else {
      $prefill_form_values = array();
    }
    foreach ($form["fields"] as $name => $field) {
      if ($field["type"] != "id") {
        $form["fields"][$name]["value"] = isset($prefill_form_values[$name]) ? htmlspecialchars_decode(preg_replace("/<br \/>/", "", $prefill_form_values[$name])) : "";
      } else {
        $form["fields"][$name]["value"] = isset($prefill_form_values[$name]) ? $prefill_form_values[$name] : array();
      }
    }
    extract($GLOBALS, EXTR_SKIP);
    ob_start();
    ?>
    <form role="form" id="<?php echo $form["name"]; ?>" action="/<?php echo $form["destination_path"]; ?>" method="post">
      <?php echo form_csrf_token(); ?>
      <?php include $form["html_form_path"]; ?>
    </form>
    <?php
    unset($_SESSION[$form_name."_errors"]);
    return ob_get_clean();
  }

  function create_form_field($type, $human_name, $properties) {
    return array_merge($properties, array(
      "type" => $type,
      "human_name" => $human_name
    ));
  }

  function create_amount_field($human_name, $properties = array()) {
    set_if_not_set($properties["min"], 0);
    set_if_not_set($properties["max"], MAX_AMOUNT);
    return create_form_field("amount", $human_name, $properties);
  }

  function create_id_field($human_name, $model, $properties = array()) {
    $properties["model"] = $model;
    return create_form_field("id", $human_name, $properties);
  }

  function create_date_field($human_name, $properties = array()) {
    return create_form_field("date", $human_name, $properties);
  }

  function create_text_field($human_name, $properties = array()) {
    set_if_not_set($properties["max"], MAX_TEXT_LENGTH);
    return create_form_field("text", $human_name, $properties);
  }

  function create_name_field($human_name, $properties = array()) {
    set_if_not_set($properties["max"], MAX_NAME_LENGTH);
    return create_form_field("name", $human_name, $properties);
  }

  function create_boolean_field($human_name, $properties = array()) {
    return create_form_field("boolean", $human_name, $properties);
  }
