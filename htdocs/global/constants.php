<?php

  $GLOBAL_PATH = "global/";
  $MODEL_PATH = "model/";
  $HELPER_PATH = "helper/";
  $CONTROLLER_PATH = "controller/";
  $LIB_PATH = "lib/";
  $VIEW_PATH = "view/";
  $ASSET_PATH = "/asset/";
  $IMG_PATH = $ASSET_PATH."img/";
  $LAYOUT_PATH = $VIEW_PATH."layout/";

  $HOST = "localhost:3000";
  $SCHEME = "http";
  $KES_ID = 0;

  $STATE = "development";
  define("MAX_AMOUNT", 10000000);
  define("MAX_TAG_STRING_LENGTH", 1000);
  define("MAX_DATE_LENGTH", 10);
  define("MAX_TERM", 10000);

  define("WEBMASTER_MAIL", "aymeric.bouzy@polytechnique.edu");
  $URL_REWRITE = true; // when changing this value, run localhost/url_rewriting.php to rewrite .htaccess file
