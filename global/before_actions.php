<?php

  function validate_input($required_parameters, $optionnal_parameters, $method = "get") {
    switch ($method) {
    case "get":
      $input_parameters = $_GET;
      break;
    case "post":
      $input_parameters = $_POST;
      break;
    }
    $valid = true;
    foreach ($required_parameters as $parameter) {
      $valid = $valid && isset($input_parameters[$parameter]);
    }
    if ($valid) {
      foreach ($input_parameters as $parameter => $value) {
        if (in_array($parameter, array_merge($required_parameters, $optionnal_parameters))) {
          switch ($parameter) {
          case "binet":
            $valid = $valid && preg_match("[a-z0-9-]+", $input_parameters[$parameter]);
            break;
          case "term":
            $valid = $valid && is_numeric("term");
            break;
          }
        }
      }
      return $valid;
    } else {
      return false;
    }
  }