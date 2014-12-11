<?php

  include LIB_PATH."fkz_fake_auth.php";

  switch ($_GET["action"]) {

  case "login":
    if (!isset($_GET['response'])) {
      frankiz_do_auth();
    } else {
      $auth = frankiz_get_response();
      $students = select_students(array("hruid" => $auth["hruid"]));
      if (empty($students)) {
        $student = create_student($auth["hruid"], $auth["first_name"]." ".$auth["last_name"], $auth["email"]);
      } else {
        $student = $students[0]["id"];
      }
      $_SESSION["student"] = $student;
      $_SESSION["notice"][] = "Tu t'es connecté avec succès.";
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
