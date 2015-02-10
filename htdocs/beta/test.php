<?php

  function mail_with_headers($to, $subject, $message) {
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html;charset=UTF-8" . "\r\n";
    $headers .= "From:         Projet Balise <balise.bugreport@gmail.com>" . "\r\n";
    $headers .= "Return-Path:  <balise.bugreport@example.com>\r\n";

    $subject = "[Projet balise] ".$subject;

    if (!mail($to, $subject, $message, $headers)) {
      error_log("Email sending to ".$to." with subject \"".$subject."\" failed.");
      return false;
    }
    var_dump($to);
    var_dump($subject);
    var_dump($headers);
    return true;
  }

  include "global/initialisation.php";

  $sent = send_email(select_students(array("name" => "Aymeric Bouzy"))[0]["id"], "Nouveau binet", "new_admin", array("binet_term" => "1/2014"));
  var_dump($sent);
  var_dump(mail("aymeric.bouzy@polytechnique.edu", "Test", "test", "From: aymeric.bouzy@localhost\r\nReturn-Path: aymeric.bouzy@localhost", "-faymeric.bouzy@localhost"));
