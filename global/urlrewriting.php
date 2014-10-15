<?php

  function standard_string($string) {
    return $string;
  }

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

      foreach(select_binets() as $binet) {
        $name = standard_string($binet["name"]);
        if (fwrite($htaccess, "RewriteRule ".$name."  ./controller/budget/index.php?binet=".$binet["id"]."&%{QUERY_STRING} [L]
                                RewriteRule ".$name."/budget  ./controller/budget/index.php?binet=".$binet["id"]."&%{QUERY_STRING} [L]
                              ") === FALSE) {
           echo ".htaccess could not be written for urlrewriting.";
           exit;
        }
      }
    }
  }
