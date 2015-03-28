<?php

  define("WEBMASTER_EMAIL", $_ENV["webmaster_email"]);
  define("STATE", "development");

  // when changing these values, run localhost/url_rewriting.php to rewrite .htaccess file.
  define("URL_REWRITE", true); // set to false if you don't want pretty urls.
  define("ROOT_PATH", ""); // set to "" to remove root_path

  define("DATABASE_USERNAME", "root");
  define("DATABASE_PASSWORD", "root");
  define("DATABASE_NAME", "projetbalise");
  define("DATABASE_HOST", "localhost");
  define("DATABASE_PORT", "8889");
  define("FRANKIZ_AUTH_KEY", "A4d!fgr6?45GF8"); // This value allows to make requests for http://localhost:3000/home/login
  define("REAL_FRANKIZ_CONNECTION", false);
