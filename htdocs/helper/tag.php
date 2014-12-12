<?php

  function search_by_tag_path($tag) {
    $binet_prefix = isset($_GET["prefix"]) && $_GET["prefix"] == "binet" ? binet_prefix($GLOBALS["binet"], $GLOBALS["term"]) : "";
    $query_array = is_selected_tag($tag, $GLOBALS["query_array"]) ? query_array_unselecting_tag($tag, $GLOBALS["query_array"]) : query_array_selecting_tag($tag, $GLOBALS["query_array"]);
    return path($_GET["action"], $_GET["controller"], "", $binet_prefix, $query_array);
  }

  function is_selected_tag($tag, $query_array) {
    return isset($query_array["select_tags"]) && in_array($tag, array($query_array["select_tags"]));
  }

  function query_array_selecting_tag($tag, $query_array) {
    $query_array["select_tags"][] = $tag;
    return $query_array;
  }

  function query_array_unselecting_tag($tag, $query_array) {
    $query_array["select_tags"] = array_diff($query_string["select_tags"], array($tag));
    return $query_array;
  }
