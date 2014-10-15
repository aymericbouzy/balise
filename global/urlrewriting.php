<?php

  function urlrewrite() {
    $htaccess = fopen("../.htaccess", "w");
  	if (!$htaccess) {
  		echo ".htaccess could not be opened for urlrewriting.";
  		exit;
  	} else {
  		if (fwrite($htaccess, "ErrorDocument  404  ./?id=404
  	                         AddDefaultCharset iso-8859-1
  	                         RewriteEngine on
  	                        ") === FALSE) {
	       echo ".htaccess could not be written for urlrewriting.";
	       exit;
	    }

      $binets = select_binets()

    }
  }
