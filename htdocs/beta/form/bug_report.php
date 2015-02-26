<?php

  $form["redirect_to_if_error"] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : path("welcome", "home");
  $form["destination_path"] = path("bug_report", "home");
  $form["html_form_path"] = VIEW_PATH."home/bug_report.php";
  $form["fields"]["report"] = create_text_field("le rapport de bug");
  $form["fields"]["information"] = create_text_field("les informations complémentaires");

  function get_information() {
    $url = isset($_SERVER["REDIRECT_URL"]) ? $_SERVER["REDIRECT_URL"] : $_SERVER["REQUEST_URI"];
    $browser = $_SERVER["HTTP_USER_AGENT"];
    $email = connected_student() ? select_student($_SESSION["student"], array("email"))["email"] : "";
    $post = array_to_string($_POST);
    $session = array_to_string($_SESSION);
    $get = array_to_string($_GET);
    $body = "\n\n\n\n———————————\n**** Ne pas modifier cette partie ****\n\nURL demandée :\t\t\t\t\t".$url."\nBrowser :\t\t\t\t\t\t\t".$browser."\npersonne connectée :\t\t\t\t".$email."\nétat de la variable \$_POST :\t\t\t".$post."\nétat de la variable \$_SESSION :\t\t".$session."\nétat de la variable \$_GET :\t\t\t".$get;
    $body = urlencode($body);
    $body = str_replace(array("+"), array(" "), $body);
    $initial_input["information"] = $body;
    return $initial_input;
  }

  $form["initialise_form"] = "get_information";
