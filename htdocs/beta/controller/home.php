<?php

  include LIB_PATH."fkz_auth.php";

  function no_useless_connection() {
    if (connected_student()) {
      redirect_to_action("");
    }
  }

  before_action("no_useless_connection", array("login"));

  switch ($_GET["action"]) {

  case "login":
    if (!isset($_GET["response"])) {
      frankiz_do_auth();
    } else {
      $auth = frankiz_get_response();
      $students = select_students(array("hruid" => $auth["hruid"]));
      if (empty($students)) {
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

  default:
    header_if(true, 403);
    exit;
  }
