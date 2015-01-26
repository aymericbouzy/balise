<?php

  include "global/initialisation.php";

  function call_at_shutdown() {
    $error = error_get_last();

    if( $error !== NULL) {
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
      $content .= "</tbody></table>";

      mail_with_headers(WEBMASTER_EMAIL, "Error ".$errno." : ".$errstr, $content);
    }
  }

  register_shutdown_function("call_at_shutdown");

  include "controller/base.php";
