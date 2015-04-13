<?php

  function before_action($function, $actions, $argument = NULL) {
    if (in_array($_GET["action"], $actions)) {
      if (is_empty($argument)) {
        call_user_func($function);
      } else {
        call_user_func($function, $argument);
      }
    }
  }

  function header_if($test, $status, $no_exit = false) {
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
      case 500:
        $header = "500 Server Error";
        break;
      }
      header("HTTP/1.1 ".$header);

      if (!isset($_SESSION["known_rejected_url"]) || $_SERVER["REQUEST_URI"] != $_SESSION["known_rejected_url"]) {
        urlrewrite();
        $_SESSION["known_rejected_url"] = $_SERVER["REQUEST_URI"];
        redirect_to_path($_SERVER["REQUEST_URI"]);
      } else {
        unset($_SESSION["known_rejected_url"]);
      }

      if (STATE == "development") {
        echo "\$_GET : ";
        var_dump($_GET);
        echo "\$_SESSION : ";
        var_dump($_SESSION);
        echo "\$_POST : ";
        var_dump($_POST);
        $backtrace = debug_backtrace();
        if (isset($backtrace[1])) {
          echo "Appelé par : ";
          var_dump($backtrace[1]["function"]);
        }
      } elseif (!is_empty($_SERVER["HTTP_REFERER"])) {
        mail_with_headers(WEBMASTER_EMAIL, get_bug_reference()." Status ".$status." : '".$header."'", "Requested URL : ".$_SERVER["REQUEST_URI"]."<br>Previous URL : ".$_SERVER["HTTP_REFERER"])."<br>".nl2br(get_debug_context());
      }

      $_GET["controller"] = "error";
      $_GET["action"] = $status;
      unset($_GET["prefix"]);
      include LAYOUT_PATH."application.php";
      if (!$no_exit) {
        exit;
      }
    }
  }

  function check_binet_term() {
    header_if(!validate_input(array("binet", "term")), 400);
    $binets = select_binets(array("clean_name" => $_GET["binet"]));
    header_if(is_empty($binets), 404);
    define("binet", $binets[0]["id"]);
    define("term", $_GET["term"]);
  }

  function check_entry($array) {
    header_if(!validate_input(array($array["model_name"])), 400);
    $criteria = $array;
    unset($criteria["model_name"]);
    $entry = call_user_func("select_".$array["model_name"], $_GET[$array["model_name"]], array_merge(array("id"), array_keys($criteria)));
    header_if(is_empty($entry), 404);
    foreach ($criteria as $column => $value) {
      header_if($value != $entry[$column], 403);
    }
    $GLOBALS[$array["model_name"]] = $entry;
  }

  function has_viewing_rights($binet, $term) {
    if (status_viewer_binet($binet, $term) || status_admin_current_binet(KES_ID)) {
      return true;
    } else {
      $terms = select_terms(array("binet" => $binet, "term" => array(">=", $term - 1), "student" => connected_student()));
      return !is_empty($terms) ||
      received_subsidy_request_from($binet);
    }
  }

  function has_editing_rights($binet, $term) {
    $current_term = current_term($binet);
    $terms_admin = select_terms(array("binet" => $binet, "term" => array(">=", $current_term), "student" => connected_student(), "rights" => editing_rights), "term");
    if (is_empty($terms_admin)) {
      return false;
    }
    $term_admin = explode("/", $terms_admin[0]["id"])[1];
    return is_numeric($term_admin) &&
      $term_admin >= $current_term &&
      $term_admin <= $term;
  }

  function has_request_viewing_rights($request) {
    $request = select_request($request, array("state", "wave", "binet", "term"));
    $wave = select_wave($request["wave"], array("binet", "term"));
    return has_viewing_rights($request["binet"], $request["term"]) || ($request["state"] != "rough_draft" && has_viewing_rights($wave["binet"], $wave["term"]));
  }

  function check_viewing_rights() {
    header_if(!has_viewing_rights(binet, term), 401);
  }

  function check_editing_rights() {
    if ($_GET["controller"] == "binet") {
      $binet = $GLOBALS["binet"]["id"];
      $term = current_term($binet);
    } else {
      $binet = binet;
      $term = term;
    }
    header_if(!has_editing_rights($binet, $term), 401);
  }

  function is_current_kessier() {
    return status_admin_current_binet(KES_ID);
  }

  function current_kessier() {
    header_if(!is_current_kessier(), 401);
  }

  function member_binet_current_term() {
    header_if(!status_admin_current_binet(binet), 401);
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
            $valid = $valid && preg_does_match("/^[a-z_]+|[0-9]+$/", $value);
            break;
          case "controller":
            $valid = $valid && preg_does_match("/^[a-z_]+$/", $value);
            break;
          case "prefix":
            $valid = $valid && in_array($value, array("binet"));
            break;
          case "tags":
            $tags = explode(" ", $value);
            foreach ($tags as $tag) {
              $valid = $valid && $tag == preg_does_match("/^([".allowed_clean_string_characters()."])+$/", $tag);
            }
            break;
          case "binet":
            $valid = $valid && preg_does_match("/^([".allowed_clean_string_characters()."])+$/", $value);
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
          case "member":
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
            INNER JOIN binet_member
            ON binet_member.binet = wave.binet AND binet_member.term = wave.term
            INNER JOIN subsidy
            ON request.id = subsidy.request
            INNER JOIN budget
            ON budget.id = subsidy.budget
            WHERE budget.binet = :binet AND binet_member.student = :student AND binet_member.rights = 0 AND request.sending_date IS NOT NULL AND wave.published = 0
            LIMIT 1";
    $req = Database::get()->prepare($sql);
    $req->bindValue(':binet', $binet, PDO::PARAM_INT);
    $req->bindValue(':student', $_SESSION["student"], PDO::PARAM_INT);
    $req->execute();
    $result = $req->fetch();
    return !is_empty($result);
  }

  function compute_query_array() {
    $query_array = array_intersect_key($_GET, array_flip(array("tags", "current_date")));
    if (!is_empty($query_array["tags"])) {
      $tags_clean_names = explode(" ", $query_array["tags"]);
      $query_array["tags"] = array();
      foreach ($tags_clean_names as $clean_name) {
        $query_array["tags"][] = select_tags(array("clean_name" => $clean_name))[0]["id"];
      }
    } else {
      unset($query_array["tags"]);
    }
    return $query_array;
  }

  function check_csrf_post() {
    header_if(!isset($_POST["csrf_token"]) || !valid_csrf_token($_POST["csrf_token"]), 401);
  }

  function check_csrf_get() {
    header_if(!isset($_GET["csrf_token"]) || !valid_csrf_token($_GET["csrf_token"]), 401);
  }
