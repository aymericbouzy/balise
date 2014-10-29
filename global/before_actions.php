<?php

  function before_action($function, $actions) {
    if (in_array($_GET["action"], $actions)) {
      call_user_func($function);
    }
  }

  function header_if($test, $status) {
    if ($test) {
      switch ($status) {
      case 400:
        $header = "400 Bad Request";
        break;
      case 401:
        $header = "401 Unauthorized";
        break;
      case 403:
        $header = "400 Forbidden";
        break;
      case 404:
        $header = "400 Not Found";
        break;
      }
      header("HTTP/1.1 ".$header);
      exit;
    }
  }

  function correct_action() {
    header_if(!validate_input(array("action")), 400);
  }

  function correct_binet_term() {
    header_if(!validate_input(array("binet", "term")), 400);
    $binets = select_binets(array("clean_name" => $_GET["binet"]));
    header_if(empty($binets), 404);
    $_GET["binet"] = $binets[0]["id"];
  }

  function kessier() {
    header_if(!status_binet_admin($KES_ID), 401);
  }

  function member_binet_term() {
    header_if(!status_binet_admin($_GET["binet"], $_GET["term"]), 401);
  }

  function watcher_binet_term() {
    header_if(!status_binet_admin($_GET["binet"], $_GET["term"]) && !status_binet_admin($KES_ID) && !watching_subsidy_requester($_GET["binet"]), 401);
  }

  function validate_input($required_parameters, $optionnal_parameters = array(), $method = "get") {
    switch ($method) {
    case "get":
      $input_parameters = $_GET;
      break;
    case "post":
      $input_parameters = $_POST;
      break;
    case "session":
      $input_parameters = $_SESSION;
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
          case "action":
            $valid = $valid && preg_match("[a-z_]+", $value);
            break;
          case "binet":
            $valid = $valid && preg_match("[a-z0-9-]+", $value);
            break;
          case "term":
            $valid = $valid && is_numeric($value);
            break;
          case "budget":
            $valid = $valid && is_numeric($value);
            break;
          case "operation":
            $valid = $valid && is_numeric($value);
            break;
          case "tag":
            $valid = $valid && is_numeric($value);
            break;
          case "wave":
            $valid = $valid && is_numeric($value);
            break;
          case "admin":
            $valid = $valid && is_numeric($value);
            break;
          case "student":
            $valid = $valid && is_numeric($value);
            break;
          }
        }
      }
      return $valid;
    } else {
      return false;
    }
  }

  function watching_subsidy_requester($binet) {
    $sql = "SELECT request.id
            FROM request
            INNER JOIN wave
            ON wave.id = request.wave
            INNER JOIN binet_admin
            ON binet_admin.binet = wave.binet AND binet_admin.term = wave.term
            INNER JOIN subsidy
            ON request.id = subsidy.request
            INNER JOIN budget
            ON budget.id = subsidy.budget
            WHERE budget.binet = :binet AND binet_admin.student = :student AND request.sent = 1 AND wave.published = 0
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindParam(':binet', $binet, PDO::PARAM_INT);
    $req->bindParma(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    return !empty($req->fetch());
  }
