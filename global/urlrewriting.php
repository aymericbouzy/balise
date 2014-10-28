<?php

  // TODO: implement function
  function clean_string($string) {
    return $string;
  }

  function path($action = "", $model_name = "", $model_id = "", $prefix = "") {
    return $prefix.(empty($model_name) ? "" : "/".$model_name).(empty($model_id) ? "" : "/".$model_id).(empty($action) ? "" : "/".$action);
  }

  function write_path_rule($htaccess, $path, $url) {
    if (fwrite($htaccess, "RewriteRule ".$path." ./controller/".$url."%{QUERY_STRING} [L]
    ") === FALSE && $_ENV["development"]) {
      echo ".htaccess could not be written for urlrewriting.";
    }
  }

  function write_scaphander_rules($htaccess, $model_name, $binet_prefix = false, $collection_actions = array("new", "create"), $member_actions = array("show", "edit", "update", "delete"), $model_id = "([0-9]+)", $model_query_string = "") {
    write_path_rule(
      $htaccess,
      path("", $model_name, "", $binet_prefix ? "binet/([a-z-]+)/([0-9]+)/" : ""),
      ($binet_prefix ? "binet/").$model_name.".php?action=index".($binet_prefix ? "&binet=$1&term=$2" : "")."&"
    );
    $collection_actions[] = "index";
    foreach ($collection_actions as $action) {
      write_path_rule(
        $htaccess,
        path($action, $model_name, "", $binet_prefix ? "binet/([a-z-]+)/([0-9]+)/" : ""),
        ($binet_prefix ? "binet/").$model_name.".php?action=".$action.($binet_prefix ? "&binet=$1&term=$2" : "")."&"
      );
    }
    $model_query_string = empty($model_query_string) ? $model_name."=$".($binet_prefix ? "3" : "1") : $model_query_string;
    foreach ($member_actions as $action) {
      write_path_rule(
        $htaccess,
        path($action, $model_name, $model_id, $binet_prefix ? "binet/([a-z-]+)/([0-9]+)/" : ""),
        ($binet_prefix ? "binet/").$model_name.".php?action=".$action.($binet_prefix ? "&binet=$1&term=$2" : "")."&".$model_query_string."&"
      );
    }
  }

  function urlrewrite() {
    $htaccess = fopen("../.htaccess", "w");
  	if (!$htaccess) {
  		echo ".htaccess could not be opened for urlrewriting.";
  		exit;
  	}
		if (fwrite($htaccess, "ErrorDocument  404  ./?id=404
	                         AddDefaultCharset iso-8859-1
	                         RewriteEngine on
	                        ") === FALSE) {
       echo ".htaccess could not be written for urlrewriting.";
       exit;
    }

    write_path_rule($htaccess, path(), "frankiz.php?action=login&");
    write_path_rule($htaccess, path("login"), "frankiz.php?action=login&");
    write_path_rule($htaccess, path("logout"), "frankiz.php?action=logout&");

    write_scaphander_rules($htaccess, "binet", false, array("new", "create"), array("show", "edit", "update", "set_subsidy_provider", "change_term", "deactivate"), "([a-z-]+)/([0-9]+)", "binet=$1&term=$2");
    write_scaphander_rules($htaccess, "budget", true);
    write_scaphander_rules($htaccess, "operation", true, array("new", "create"), array("show", "edit", "update", "delete", "validate"));
    write_scaphander_rules($htaccess, "request", true);
    write_scaphander_rules($htaccess, "tag", false, array("create"), array("show"));
    write_scaphander_rules($htaccess, "binet", false, array("new", "create"), array("show", "edit", "update"));
    write_scaphander_rules($htaccess, "wave", false, array(), array("show"));
    write_scaphander_rules($htaccess, "wave", true, array("new", "create"), array("show", "edit", "update"));
    write_scaphander_rules($htaccess, "admin", true, array("new", "create"), array("delete"));
    write_scaphander_rules($htaccess, "operation", false, array("new", "create"), array("show", "edit", "update", "validate"));
  }
