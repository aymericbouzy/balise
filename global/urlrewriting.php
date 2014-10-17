<?php

  function standard_string($string) {
    return $string;
  }

  function standard_name_binet($binet) {
    return standard_string(select_binet($binet)["name"]);
  }

  function login_path() {
    return "login";
  }

  function binet_index_path($binet) {
    return "binet/".standard_name_binet($binet);
  }

  function binet_budget_index_path($binet) {
    return "binet/".standard_name_binet($binet)."/budget";
  }

  function write_path_rule($htaccess, $path, $url) {
    if (fwrite($htaccess, "RewriteRule ".$path." ./controller/".$url."%{QUERY_STRING} [L]
    ") === FALSE && $_ENV["development"]) {
      echo ".htaccess could not be written for urlrewriting.";
    }
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

      write_path_rule($htaccess, login_path(), "frankiz/login.php?");


      foreach(select_binets() as $binet) {
        write_path_rule($htaccess, binet_index_path($binet["id"]), "binet/budget/index.php?binet=".$binet["id"]."&");
        write_path_rule($htaccess, binet_budget_index_path($binet["id"]), "binet/budget/index.php?binet=".$binet["id"]."&");
      }
    }
  }
