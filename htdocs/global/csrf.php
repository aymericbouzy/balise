<?php

  function generate_csrf_token() {
    $csrf_token = md5(rand());
    $_SESSION["csrf_token"] = $csrf_token;
    return $csrf_token;
  }

  function valid_csrf_token($csrf_token) {
    if (empty($_SESSION["csrf_token"])) {
      return false;
    }
    $csrf_session_token = $_SESSION["csrf_token"];
    unset($_SESSION["csrf_token"]);
    return $csrf_token == $csrf_session_token;
  }
