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

      if (STATE == "development") {
        echo "\$_GET : ";
        var_dump($_GET);
        echo "\$_SESSION : ";
        var_dump($_SESSION);
        echo "\$_POST : ";
        var_dump($_POST);
      }

      $_GET["controller"] = "error";
      $_GET["action"] = $status;
      unset($_GET["prefix"]);
      include LAYOUT_PATH."application.php";
      exit;
    }
  }

  function check_binet_term() {
    header_if(!validate_input(array("binet", "term")), 400);
    $binets = select_binets(array("clean_name" => $_GET["binet"]));
    header_if(empty($binets), 404);
    $GLOBALS["binet"] = $binets[0]["id"];
    $binet_terms = select_terms(array("binet" => $GLOBALS["binet"], "term" => $_GET["term"]));
    if (empty($binet_terms)) {
      $_SESSION["error"][] = "Il n'y a aucun administrateur pour ce mandat et ce binet.";
    }
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
    $_SESSION[$array["model_name"]]["errors"] = array();
    $_SESSION[$array["model_name"]] = $_POST;
    $array["str_fields"] = isset($array["str_fields"]) ? $array["str_fields"] : array();
    $array["int_fields"] = isset($array["int_fields"]) ? $array["int_fields"] : array();
    $array["amount_fields"] = isset($array["amount_fields"]) ? $array["amount_fields"] : array();
    $array["other_fields"] = isset($array["other_fields"]) ? $array["other_fields"] : array();

    foreach (array_merge($array["str_fields"], $array["int_fields"], $array["amount_fields"], $array["other_fields"]) as $index => $field) {
      if (!isset($_POST[$field[0]]) || empty($_POST[$field[0]])) {
        if (!isset($array["optional"]) || !in_array($field[0], $array["optional"])) {
          $_SESSION[$array["model_name"]]["errors"][] = $field[0];
        } else {
          unset($array["str_fields"][$index]);
          unset($array["int_fields"][$index]);
          unset($array["amount_fields"][$index]);
        }
      }
    }

    if (!empty($_SESSION[$array["model_name"]]["errors"])) {
      $string_error = "Vous n'avez pas rempli tous les champs obligatoires. Il manque ";
      $string_error .= count($_SESSION[$array["model_name"]]["errors"]) > 1 ? "les champs suivants" : "le champ suivant";
      $string_error .= " : ";
      foreach ($_SESSION[$array["model_name"]]["errors"] as $index => $field) {
        $string_error .= translate_form_field($field);
        switch (count($_SESSION[$array["model_name"]]["errors"]) - $index) {
          case 1 :
          $string_error .= ".";
          break;
          case 2 :
          $string_error .= " et ";
          break;
          default :
          $string_error .= ", ";
        }
      }
      $_SESSION["error"][] = $string_error;
    }

    foreach ($array["str_fields"] as $field) {
      $_POST[$field[0]] = substr(htmlspecialchars($_POST[$field[0]]), 0, $field[1]);
    }

    foreach ($array["amount_fields"] as $field) {
      if (is_numeric($_POST[$field[0]])) {
        $_POST[$field[0]] = floor($_POST[$field[0]] * 100);
      }
    }

    foreach (array_merge($array["amount_fields"], $array["int_fields"]) as $field) {
      if (!is_numeric($_POST[$field[0]]) || $_POST[$field[0]] < 0 || $_POST[$field[0]] > $field[1]) {
        $_SESSION["error"][] = "La valeur entrée pour le champ \"".translate_form_field($field[0])."\" n'est pas valide.";
        $_SESSION[$array["model_name"]]["errors"][] = $field[0];
      }
    }

    foreach ($array["other_fields"] as $field) {
      if (!call_user_func($field[1], $_POST[$field[0]])) {
        $readable_field = translate_form_field($field[0]);
        if (!empty($readable_field)) {
          $_SESSION["error"][] = "La valeur entrée pour le champ \"".$readable_field."\" n'est pas valide.";
        }
        $_SESSION[$array["model_name"]]["errors"][] = $field[0];
      }
    }

    if (isset($array["tags_string"]) && $array["tags_string"]) {
      if (!empty($_POST["tags_string"])) {
        foreach (explode(";", $_POST["tags_string"]) as $tag_name) {
          $tag_name = remove_exterior_spaces($tag_name);
          $tags = select_tags(array("clean_name" => clean_string($tag_name)));
          if (empty($tags)) {
            $_SESSION["tag_to_create"] = $tag_name;
          } else {
            $GLOBALS["tags"][] = $tags[0]["id"];
          }
        }
      } else {
        $GLOBALS["tags"] = array();
      }
    }

    if (!empty($_SESSION[$array["model_name"]]["errors"])) {
      redirect_to_path($array["redirect_to"]);
    }

    if (!empty($_SESSION["tag_to_create"])) {
      $_SESSION["return_to"] = binet_prefix($GLOBALS["binet"], $GLOBALS["term"]);
      redirect_to_path(path("new", "tag"));
    }

    unset($_SESSION[$array["model_name"]]);
  }

  function has_viewing_rights($binet, $term) {
    return status_admin_current_binet(KES_ID) ||
      !empty(select_terms(array("binet" => $binet, "term" => array(">=", current_term($binet)), "student" => $_SESSION["student"]))) ||
      received_subsidy_request_from($binet);
  }

  function has_editing_rights($binet, $term) {
    $current_term = current_term($binet);
    $terms_admin = select_terms(array("binet" => $binet, "term" => array(">=", $current_term), "student" => $_SESSION["student"]), "term");
    if (empty($terms_admin)) {
      return false;
    }
    $term_admin = explode("/", $terms_admin[0]["id"])[1];
    return is_numeric($term_admin) &&
      $term_admin >= $current_term &&
      $term_admin <= $term;
  }

  function check_viewing_rights() {
    header_if(!has_viewing_rights($GLOBALS["binet"], $GLOBALS["term"]), 401);
  }

  function check_editing_rights() {
    header_if(!has_editing_rights($GLOBALS["binet"], $GLOBALS["term"]), 401);
  }

  // useless
  function kessier() {
    header_if(!status_admin_binet(KES_ID), 401);
  }

  function current_kessier() {
    header_if(!status_admin_current_binet(KES_ID), 401);
  }

  // useless
  function member_binet_term() {
    header_if(!status_admin_binet($GLOBALS["binet"], $GLOBALS["term"]), 401);
  }

  function member_binet_current_term() {
    header_if(!status_admin_current_binet($GLOBALS["binet"]), 401);
  }

  function validate_input($required_parameters, $optional_parameters = array(), $method = "get") {
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
        if (in_array($parameter, array_merge($required_parameters, $optional_parameters))) {
          switch ($parameter) {
          case "action":
            $valid = $valid && preg_does_match("/^[a-z_]+$/", $value);
            break;
          case "controller":
            $valid = $valid && preg_does_match("/^[a-z_]+$/", $value);
            break;
          case "prefix":
            $valid = $valid && in_array($value, array("binet"));
            break;
          case "tags":
            $valid = $valid && preg_does_match("/^([a-z_]+)(\+[a-z_]+)*$/", $value);
            break;
          case "binet":
            $valid = $valid && preg_does_match("/^([a-z0-9-])+$/", $value);
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

  function received_subsidy_request_from($binet) {
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
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    return !empty($req->fetch());
  }

  function compute_query_array() {
    $query_array = array_intersect_key($_GET, array_flip(array("tags")));
    if (!empty($query_array["tags"])) {
      $tags_clean_names = explode("+", $query_array["tags"]);
      $query_array["tags"] = array();
      foreach ($tags_clean_names as $clean_name) {
        $query_array["tags"][] = select_tags(array("clean_name" => $clean_name))[0]["id"];
      }
    }
    return $query_array;
  }

  function check_csrf_post() {
    header_if(!isset($_POST["csrf_token"]) || !valid_csrf_token($_POST["csrf_token"]), 401);
  }

  function check_csrf_get() {
    header_if(!isset($_GET["csrf_token"]) || !valid_csrf_token($_GET["csrf_token"]), 401);
  }
