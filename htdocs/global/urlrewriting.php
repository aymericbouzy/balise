<?php

  // TODO: implement function
  function clean_string($string) {
    return $string;
  }

  function path($action, $model_name, $model_id = "", $prefix = "", $query_array = array(), $include_csrf = false) {
    $query_string = "";
    $first = $GLOBALS["STATE"] != "development";
    if ($include_csrf) {
      $query_array[] = $_SESSION["csrf_token"];
    }
    foreach ($query_array as $key => $value) {
      if (!empty($value)) {
        if ($first) {
          $query_string .= "?";
          $first = false;
        } else {
          $query_string .= "&";
        }
        if ($key == "tags") {
          $query_string .= "tags=".implode("+", $value);
        } else {
          $query_string .= $key."=".$value;
        }
      }
    }
    if (!$GLOBALS["URL_REWRITE"]) {
      if (empty($prefix)) {
        $prefix_string = "";
      } else {
        $prefix_elements = explode("/", $prefix);
        $prefix_string = "&prefix=".$prefix_elements[0]."&binet=".$prefix_elements[1]."&term=".$prefix_elements[2];
      }

      $action = empty($action) ? "index" : $action;
      return "index.php?controller=".$model_name."&action=".$action.(empty($model_id) ? "" : "&".$model_name."=".$model_id).$prefix_string.$query_string;
    }
    return (empty($prefix) ? "" : $prefix."/").$model_name.(empty($model_id) ? "" : "/".$model_id).(empty($action) ? "" : "/".$action)."/".$query_string;
  }

  function write_path_rule($htaccess, $path, $url) {
    if (fwrite($htaccess, "RewriteRule ^".$path."$ ./index.php?".$url."%{QUERY_STRING} [L,NC,QSA]
    ") === FALSE) {
      echo ".htaccess could not be written for urlrewriting.";
    }
  }

  function write_controller_rules($htaccess, $hash) {
    $hash["except"] = $hash["except"] ?: array();
    $hash["action_on_collection"] = $hash["action_on_collection"] ?: array();
    $hash["action_on_member"] = $hash["action_on_member"] ?: array();

    $collection_actions = array_merge(array_diff(array("index", "new", "create"), $hash["except"]), $hash["action_on_collection"]);
    $member_actions = array_merge(array_diff(array("show", "edit", "update", "delete"), $hash["except"]), $hash["action_on_member"]);
    if (!isset($hash["root"])) {
      $hash["root"] = "index";
    }
    write_path_rule(
      $htaccess,
      path("", $hash["controller"], "", $hash["binet_prefix"] ? "binet/([a-z-]+)/([0-9]+)" : ""),
      "controller=".$hash["controller"].($hash["binet_prefix"] ? "&prefix=binet" : "")."&action=".$hash["root"].($hash["binet_prefix"] ? "&binet=$1&term=$2" : "")."&"
    );
    foreach ($collection_actions as $action) {
      write_path_rule(
        $htaccess,
        path($action, $hash["controller"], "", $hash["binet_prefix"] ? "binet/([a-z-]+)/([0-9]+)" : ""),
        "controller=".$hash["controller"].($hash["binet_prefix"] ? "&prefix=binet" : "")."&action=".$action.($hash["binet_prefix"] ? "&binet=$1&term=$2" : "")."&"
      );
    }
    foreach ($member_actions as $action) {
      write_path_rule(
        $htaccess,
        path($action, $hash["controller"], "([0-9]+)", $hash["binet_prefix"] ? "binet/([a-z-]+)/([0-9]+)" : ""),
        "controller=".$hash["controller"].($hash["binet_prefix"] ? "&prefix=binet" : "")."&action=".$action.($hash["binet_prefix"] ? "&binet=$1&term=$2" : "")."&".$hash["controller"]."=$".($hash["binet_prefix"] ? "3" : "1")."&"
      );
    }
  }

  function urlrewrite() {
    $htaccess = fopen("./.htaccess", "w");
  	if (!$htaccess) {
  		echo ".htaccess could not be opened for urlrewriting.";
  		exit;
  	}
		if (fwrite($htaccess, "ErrorDocument  404  ./index.php?controller=error&action=404
	                         AddDefaultCharset iso-8859-1
	                         RewriteEngine ".($GLOBALS["URL_REWRITE"] ? "on" : "off")."
                           RewriteRule ^(.*[^/])$ $1/
	                        ") === FALSE) {
       echo ".htaccess could not be written for urlrewriting.";
       exit;
    }

    write_path_rule($htaccess, "", "controller=home&action=welcome&");
    write_controller_rules($htaccess, array("controller" => "home", "except" => array("new", "create", "show", "edit", "update", "delete"), "action_on_collection" => array("login", "logout", "welcome")));
    write_controller_rules($htaccess, array("controller" => "binet", "except" => array("delete"), "action_on_member" => array("set_subsidy_provider", "change_term", "set_term", "deactivate", "validation"), "action_on_collection" => array("admin")));
    write_controller_rules($htaccess, array("controller" => "operation", "except" => array("delete"), "action_on_member" => array("validate", "reject"), "action_on_collection" => array("new_expense", "new_income")));
    write_controller_rules($htaccess, array("controller" => "tag", "except" => array("edit", "update", "delete")));
    write_controller_rules($htaccess, array("controller" => "wave", "except" => array("new", "create", "edit", "update", "delete")));

    write_controller_rules($htaccess, array("controller" => "admin", "binet_prefix" => true, "except" => array("show", "edit", "update")));
    write_controller_rules($htaccess, array("controller" => "budget", "binet_prefix" => true, "action_on_collection" => array("new_expense", "new_income")));
    write_controller_rules($htaccess, array("controller" => "operation", "binet_prefix" => true, "action_on_member" => array("validate"), "action_on_collection" => array("new_expense", "new_income")));
    write_controller_rules($htaccess, array("controller" => "request", "binet_prefix" => true, "action_on_member" => array("send")));
    write_controller_rules($htaccess, array("controller" => "wave", "binet_prefix" => true, "except" => array("delete"), "action_on_member" => array("publish")));

    fclose($htaccess);
  }
