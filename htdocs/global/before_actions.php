<?php

  function before_action($function, $actions, $argument = NULL) {
    if (in_array($_GET["action"], $actions)) {
      if (empty($argument)) {
        call_user_func($function);
      } else {
        call_user_func($function, $argument);
      }

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
        $header = "403 Forbidden";
        break;
      case 404:
        $header = "404 Not Found";
        break;
      }
      header("HTTP/1.1 ".$header);

      $_GET["controller"] = "error";
      $_GET["action"] = $status;
      unset($_GET["prefix"]);
      include $LAYOUT_PATH."application.php";
      exit;
    }
  }

  function check_binet_term() {
    header_if(!validate_input(array("binet", "term")), 400);
    $binets = select_binets(array("clean_name" => $_GET["binet"]));
    header_if(empty($binets), 404);
    $GLOBALS["binet"] = $binets[0]["id"];
    $GLOBALS["term"] = $_GET["term"];
  }

  function check_entry($array) {
    header_if(!validate_input(array($array["model_name"])), 400);
    $criteria = $array;
    unset($criteria["model_name"]);
    $entry = call_user_func("select_".$array["model_name"], $_GET[$array["model_name"]], array_merge(array("id"), array_keys($criteria)));
    header_if(empty($entry), 404);
    foreach ($criteria as $column => $value) {
      header_if($value != $entry[$column], 403);
    }
    $GLOBALS[$array["model_name"]] = $entry;
  }

  function check_form_input($array) {
    $_SESSION[$array["model_name"]] = $_POST;
    $array["str_fields"] = $array["str_fields"] ?: array();
    $array["int_fields"] = $array["int_fields"] ?: array();
    $array["amount_fields"] = $array["ammount_fields"] ?: array();

    foreach (array_merge($array["str_fields"], $array["int_fields"], $array["amount_fields"]) as $field) {
      if (!isset($_POST[$field[0]])) {
        if (!isset($array["optional"]) || !in_array($field[0], $array["optionnal"])) {
          $_SESSION[$array["model_name"]]["errors"][] = $field[0];
        } else {
          unset($array["str_fields"][$field[0]]);
          unset($array["int_fields"][$field[0]]);
          unset($array["amount_fields"][$field[0]]);
        }
      }
    }

    foreach ($array["str_fields"] as $field) {
      $_POST[$field[0]] = substr(htmlspecialchars($_POST[$fields[0]]), 0, $field[1]);
    }

    foreach ($array["amount_fields"] as $field) {
      $_POST[$field[0]] = floor($_POST[$field[0]] * 100);
    }

    foreach (array_merge($array["amount_fields"], $array["int_fields"]) as $field) {
      if (!is_numeric($_POST[$field[0]]) || $_POST[$field[0]] < 0 || $_POST[$field[0]] > $field[1]) {
        $_SESSION[$array["model_name"]]["errors"][] = $field[0];
      }
    }

    if ($array["tags_string"]) {
      if (!empty($_POST["tags_string"])) {
        foreach (explode($_POST["tags_string"], ";") as $tag_name) {
          $tag = select_tags(array("clean_name" => clean_string($tag_name)));
          if (empty($tag)) {
            $_SESSION["tag_to_create"] = remove_exterior_spaces($tag_name);
          } else {
            $GLOBALS["tags"][] = $tag[0]["id"];
          }
        }
      } else {
        $GLOBALS["tags"] = array();
      }
    }

    if (!empty($_SESSION[$array["model_name"]]["errors"])) {
      redirect_to($array["redirect_to"]);
    }

    if (!empty($_SESSION["tag_to_create"])) {
      $_SESSION["return_to"] = $_SERVER["??"];
      redirect_to(array("path" => path("new", "tag")));
    }

    unset($_SESSION[$array["model_name"]]);
  }

  function kessier() {
    header_if(!status_binet_admin($KES_ID), 401);
  }

  function current_kessier() {
    header_if(!status_binet_admin($KES_ID, select_binet($KES_ID, array("current_term"))["current_term"]), 401);
  }

  function member_binet_term() {
    header_if(!status_binet_admin($_GET["binet"], $_GET["term"]), 401);
  }

  function watcher_binet_term() {
    header_if(!status_binet_admin($_GET["binet"]) && !status_binet_admin($KES_ID) && !watching_subsidy_requester($_GET["binet"]), 401);
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
          case "controller":
            $valid = $valid && preg_match("[a-z_]+", $value);
            break;
          case "prefix":
            $valid = $valid && in_array($value, array("binet"));
            break;
          case "tags":
            $valid = $valid && preg_match("([a-z_]+)(\+[a-z_]+)*", $value);
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

  function compute_query_array() {
    $query_array = array_intersect_key($_GET, array_flip("tags"));
    if (!empty($query_array("tags"))) {
      $tags_clean_names = explode($query_array["tags"], "+");
      $query_array["tags"] = array();
      foreach ($tags_clean_names as $clean_name) {
        $query_array["tags"][] = select_tags(array("clean_name" => $clean_name))[0]["id"];
      }
    }
    return $query_array;
  }
