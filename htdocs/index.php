<?php

  function call_at_shutdown() {
    $error = error_get_last();

    if ($error !== NULL && STATE != "development" && !isset($GLOBALS["error_already_sent"])) {
      send_error_by_mail($error);
    }
  }

  function send_error_by_mail($error) {
    $errno   = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr  = $error["message"];

    $trace = print_r(debug_backtrace(false), true);

    $content  = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
    $content .= "<tr valign='top'><td><b>Error</b></td><td><pre>$errstr</pre></td></tr>";
    $content .= "<tr valign='top'><td><b>Errno</b></td><td><pre>$errno</pre></td></tr>";
    $content .= "<tr valign='top'><td><b>File</b></td><td>$errfile</td></tr>";
    $content .= "<tr valign='top'><td><b>Line</b></td><td>$errline</td></tr>";
    $content .= "<tr valign='top'><td><b>Trace</b></td><td><pre>$trace</pre></td></tr>";
    $content .= "<tr valign='top'><td><b>Context</b></td><td><pre>".nl2br(get_debug_context())."</pre></td></tr>";
    $content .= "</tbody></table>";

    return mail_with_headers(WEBMASTER_EMAIL, "Error ".$errno." : '".$errstr."'", $content);
  }

  function mail_with_headers($to, $subject, $message, $reply_to = "Balise <balise.bugreport@gmail.com>") {
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html;charset=UTF-8" . "\r\n";
    $headers .= "From:         Balise <no-reply@balise.bin>" . "\r\n";
    $headers .= "Reply-To:     " . $reply_to . "\r\n";

    $subject = "[Balise] ".$subject;

    if (!mail($to, $subject, $message, $headers)) {
      error_log("Email sending to ".$to." with subject \"".$subject."\" failed.");
      return false;
    }
    return true;
  }

  function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (STATE != "development") {
      ob_get_clean();
      $GLOBALS["error_already_sent"] = send_error_by_mail(array("type" => $severity, "file" => $filename, "line" => $lineno, "message" => $message));
      header_if(true, 500, true);
    }
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }

  register_shutdown_function("call_at_shutdown");
  set_error_handler("exceptions_error_handler");

  include "global/initialisation.php";

  ob_start();
  include "controller/base.php";
  echo ob_get_clean();
