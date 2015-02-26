<?php

  include LIB_PATH.(REAL_FRANKIZ_CONNECTION ? "fkz_auth.php" : "fkz_fake_auth.php");

  function no_useless_connection() {
    if (connected_student()) {
      redirect_to_action("");
    }
  }

  function check_fake_auth() {
    header_if(REAL_FRANKIZ_CONNECTION, 403);
  }

  before_action("no_useless_connection", array("login", "welcome"));
  before_action("check_fake_auth", array("chose_identity"));
  before_action("check_form", array("bug_report"), "bug_report");

  switch ($_GET["action"]) {

  case "login":
    if (!isset($_GET["response"])) {
      frankiz_do_auth();
    } else {
      $auth = frankiz_get_response();
      $students = select_students(array("hruid" => $auth["hruid"]));
      if (is_empty($students)) {
        $student = create_student($auth["hruid"], $auth["firstname"]." ".$auth["lastname"], $auth["email"]);
        $_SESSION["notice"][] =
          "Bienvenu sur le site balise ! Tu trouveras ici toutes les informations pour gérer la trésorerie de tes binets,
          des outils pour tenir ta trésorerie et faire des analyses ; tu pourras y faire tes demandes de subventions et y
          retrouver toutes les informations publiques sur la trésorerie des binets, comme par exemple les subventions qui
          leur ont été attribuées.";
      } else {
        $student = $students[0]["id"];
      }
      $_SESSION["student"] = $student;
      $_SESSION["notice"][] = "Tu t'es connecté avec succès.";
      if (isset($_SESSION["redirect_to_after_connection"])) {
        $redirect_to_after_connection = $_SESSION["redirect_to_after_connection"];
        unset($_SESSION["redirect_to_after_connection"]);
        redirect_to_path($redirect_to_after_connection);
      }
      redirect_to_action("");
    }
    break;

  case "logout":
    unset($_SESSION["student"]);
    $_SESSION["notice"][] = "Tu t'es déconnecté avec succès.";
    redirect_to_action("welcome");
    break;

  case "index":
    break;

  case "welcome":
    break;

  case "chose_identity":
    break;

  case "bug_report":
    $reference = substr(md5(rand()), 0, 10);
    mail_with_headers(WEBMASTER_EMAIL, "[bug #".$reference."]", $_POST["report"]."\n\n".$_POST["information"]);
    $_SESSION["notice"][] = "Le rapport de bug a bien été envoyé, merci pour ta contribution !";
    redirect_to_path($bug_report_form["redirect_to_if_error"]);
    break;

  default:
    header_if(true, 403);
    exit;
  }
