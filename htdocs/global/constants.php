<?php

  define("WEBMASTER_EMAIL", $webmaster_email);

  // when changing these values, run localhost/url_rewriting.php to rewrite .htaccess file.
  define("STATE", $state);
  define("URL_REWRITE", true); // set to false if you don't want pretty urls.
  define("ROOT_PATH", ""); // set to "" to remove root_path

  define("DATABASE_USERNAME", $database_username);
  define("DATABASE_PASSWORD", $database_password);
  define("DATABASE_NAME", $database_name);
  define("DATABASE_HOST", $database_host);
  define("DATABASE_PORT", $database_port);
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
  define("FORM_PATH", "form/");

  define("KES_ID", 1);

  define("MAX_AMOUNT", 1000000000);
  define("MAX_TEXT_LENGTH", 65000);
  define("MAX_NAME_LENGTH", 127);
  define("MAX_TERM", 2100);
