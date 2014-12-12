<?php

  define("WEBMASTER_EMAIL", empty($_ENV["webmaster_email"]) ? "aymeric.bouzy@polytechnique.edu" : $_ENV["webmaster_email"]);
  define("STATE", empty($_ENV["state"]) ? "development" : $_ENV["state"]);
  define("URL_REWRITE", empty($_ENV["url_rewrite"]) ? true : $_ENV["url_rewrite"]); // when changing this value, run localhost/url_rewriting.php to rewrite .htaccess file
  define("ROOT_PATH", isset($_ENV["root_path"]) ? $_ENV["root_path"] : "");

  define("DATABASE_PASSWORD", $_ENV["database_password"]);
  define("FRANKIZ_AUTH_KEY", $_ENV["frankiz_auth_key"]);

  define("GLOBAL_PATH", "global/");
  define("MODEL_PATH", "model/");
  define("HELPER_PATH", "helper/");
  define("CONTROLLER_PATH", "controller/");
  define("LIB_PATH", "lib/");
  define("VIEW_PATH", "view/");
  define("ASSET_PATH", (empty(ROOT_PATH) ? "" : "/".ROOT_PATH)."/asset/");
  define("IMG_PATH", ASSET_PATH."img/");
  define("LAYOUT_PATH", VIEW_PATH."layout/");

  define("KES_ID", 0);

  define("MAX_AMOUNT", 10000000);
  define("MAX_TAG_STRING_LENGTH", 1000);
  define("MAX_DATE_LENGTH", 10);
  define("MAX_TERM", 10000);
