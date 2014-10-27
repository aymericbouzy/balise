<?php

  function clean_string($string) {
    return $string;
  }

  function login_path() {
    return "login";
  }

  function logout_path() {
    return "logout";
  }

  function binet_index_path($binet) {
    return "binet/".select_binet($binet)["clean_name"];
  }

  function binet_budget_index_path($binet) {
    return "binet/".select_binet($binet)["clean_name"]."/budget";
  }

  function binet_budget_show_path($binet, $budget) {
    return "binet/".select_binet($binet)["clean_name"]."/budget/".$budget;
  }

  function binet_budget_new_path($binet) {
    return "binet/".select_binet($binet)["clean_name"]."/budget/new";
  }

  function binet_budget_create_path($binet) {
    return "binet/".select_binet($binet)["clean_name"]."/budget/create";
  }

  function binet_budget_edit_path($binet, $budget) {
    return "binet/".select_binet($binet)["clean_name"]."/budget/".$budget."/edit";
  }

  function binet_budget_update_path($binet, $budget) {
    return "binet/".select_binet($binet)["clean_name"]."/budget/".$budget."/update";
  }

  function binet_budget_delete_path($binet, $budget) {
    return "binet/".select_binet($binet)["clean_name"]."/budget/".$budget."/delete";
  }

  function binet_operation_index_path($binet) {
    return "binet/".select_binet($binet)["clean_name"]."/operation";
  }

  function binet_operation_show_path($binet, $operation) {
    return "binet/".select_binet($binet)["clean_name"]."/operation/".$operation;
  }

  function binet_operation_new_path($binet) {
    return "binet/".select_binet($binet)["clean_name"]."/operation/new";
  }

  function binet_operation_create_path($binet) {
    return "binet/".select_binet($binet)["clean_name"]."/operation/create";
  }

  function binet_operation_edit_path($binet, $operation) {
    return "binet/".select_binet($binet)["clean_name"]."/operation/".$operation."/edit";
  }

  function binet_operation_update_path($binet, $operation) {
    return "binet/".select_binet($binet)["clean_name"]."/operation/".$operation."/update";
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

      write_path_rule($htaccess, login_path(), "frankiz.php?action=login&");
      write_path_rule($htaccess, logout_path(), "frankiz.php?action=logout&");

      foreach(select_binets() as $binet) {
        write_path_rule($htaccess, binet_index_path($binet["id"]), "binet/budget.php?action=index&binet=".$binet["id"]."&");
        write_path_rule($htaccess, binet_budget_index_path($binet["id"]), "binet/budget.php?action=index&binet=".$binet["id"]."&");
        foreach (select_budgets(array("binet" => $binet["id"])) as $budget) {
          write_path_rule($htaccess, binet_budget_show_path($binet["id"], $budget["id"]), "binet/budget.php?action=index&binet=".$binet["id"]."&budget=".$budget["id"]."&");
          write_path_rule($htaccess, binet_budget_edit_path($binet["id"], $budget["id"]), "binet/budget.php?action=edit&binet=".$binet["id"]."&budget=".$budget["id"]."&");
          write_path_rule($htaccess, binet_budget_update_path($binet["id"], $budget["id"]), "binet/budget.php?action=update&binet=".$binet["id"]."&budget=".$budget["id"]."&");
          write_path_rule($htaccess, binet_budget_delete_path($binet["id"], $budget["id"]), "binet/budget.php?action=delete&binet=".$binet["id"]."&budget=".$budget["id"]."&");
        }
        write_path_rule($htaccess, binet_budget_new_path($binet["id"]), "binet/budget.php?action=new&binet=".$binet["id"]."&");
        write_path_rule($htaccess, binet_budget_create_path($binet["id"]), "binet/budget.php?action=create&binet=".$binet["id"]."&");
      }
    }
  }
