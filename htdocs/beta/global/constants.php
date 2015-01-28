<?php

  define("WEBMASTER_EMAIL", $webmaster_email);
  define("STATE", $state);
  define("URL_REWRITE", true); // when changing this value, run localhost/url_rewriting.php to rewrite .htaccess file. set to false if you don't want pretty urls.
  define("ROOT_PATH", "beta/"); // set to "" to remove root_path

  define("DATABASE_USERNAME", $database_username);
  define("DATABASE_PASSWORD", $database_password);
  define("FRANKIZ_AUTH_KEY", $frankiz_auth_key);
  define("REAL_FRANKIZ_CONNECTION", $real_frankiz_connection);

  define("GLOBAL_PATH", "global/");
  define("MODEL_PATH", "model/");
  define("HELPER_PATH", "helper/");
  define("CONTROLLER_PATH", "controller/");
  define("LIB_PATH", "lib/");
  define("VIEW_PATH", "view/");
  define("ASSET_PATH", "/".ROOT_PATH."asset/");
  define("IMG_PATH", ASSET_PATH."img/");
  define("LAYOUT_PATH", VIEW_PATH."layout/");
  define("EMAIL_PATH", VIEW_PATH."email/");

  define("KES_ID", 2);

  define("MAX_AMOUNT", 10000000);
  define("MAX_TAG_STRING_LENGTH", 1000);
  define("MAX_DATE_LENGTH", 10);
  define("MAX_TERM", 10000);
  define("MAX_TEXT_LENGTH", 65000);
