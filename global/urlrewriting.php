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
    ") === FALSE) {
      echo ".htaccess could not be written for urlrewriting.";
    }
  }

  function write_controller_rules($htaccess, $hash) {
    $collection_actions = array_merge(array_diff(array("index", "new", "create"), $hash["except"]), $hash["action_on_collection"]);
    $member_actions = array_merge(array_diff(array("show", "edit", "update", "delete"), $hash["except"]), $hash["action_on_member"]);
    if (!isset($hash["root"])) {
      $hash["root"] = "index";
    }
    write_path_rule(
      $htaccess,
      path("", $hash["controller"], "", $hash["binet_prefix"] ? "binet/([a-z-]+)/([0-9]+)/" : ""),
      "base.php?controller=".$hash["controller"].($hash["binet_prefix"] ? "&prefix=binet")."&action=".$hash["root"].($hash["binet_prefix"] ? "&binet=$1&term=$2" : "")."&"
    );
    foreach ($collection_actions as $action) {
      write_path_rule(
        $htaccess,
        path($action, $hash["controller"], "", $hash["binet_prefix"] ? "binet/([a-z-]+)/([0-9]+)/" : ""),
        "base.php?controller=".$hash["controller"].($hash["binet_prefix"] ? "&prefix=binet")."&action=".$action.($hash["binet_prefix"] ? "&binet=$1&term=$2" : "")."&"
      );
    }
    foreach ($member_actions as $action) {
      write_path_rule(
        $htaccess,
        path($action, $hash["controller"], "([0-9]+)", $hash["binet_prefix"] ? "binet/([a-z-]+)/([0-9]+)/" : ""),
        "base.php?controller=".$hash["controller"].($hash["binet_prefix"] ? "&prefix=binet")."&action=".$action.($hash["binet_prefix"] ? "&binet=$1&term=$2" : "")."&".$$hash["controller"]."=$".($hash["binet_prefix"] ? "3" : "1")."&"
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

    write_controller_rules($htaccess, array("controller" => "frankiz", "except" => array("index", "new", "create", "show", "edit", "update", "delete"), "action_on_collection" => array("login", "logout"), "root" => "login"));
    write_controller_rules($htaccess, array("controller" => "binet", "except" => array("delete"), "action_on_member" => array("set_subsidy_provider", "change_term", "deactivate", "validation")));
    write_controller_rules($htaccess, array("controller" => "operation", "except" => array("delete"), "action_on_member" => array("validate", "reject")));
    write_controller_rules($htaccess, array("controller" => "tag", "except" => array("new", "edit", "update", "delete")));
    write_controller_rules($htaccess, array("controller" => "wave", "except" => array("new", "create", "edit", "update", "delete"));

    write_controller_rules($htaccess, array("controller" => "admin", "binet_prefix" => true, "except" => array("show", "edit", "update")));
    write_controller_rules($htaccess, array("controller" => "budget", "binet_prefix" => true));
    write_controller_rules($htaccess, array("controller" => "operation", "binet_prefix" => true, "action_on_member" => array("validate")));
    write_controller_rules($htaccess, array("controller" => "request", "binet_prefix" => true, "action_on_member" => array("send")));
    write_controller_rules($htaccess, array("controller" => "wave", "binet_prefix" => true, "except" => array("delete"), "action_on_member" => array("publish")));
  }
