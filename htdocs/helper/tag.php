<?php

  function search_by_tag_path($tag) {
    $binet_prefix = isset($_GET["prefix"]) && $_GET["prefix"] == "binet" ? binet_prefix(binet, term) : "";
    $query_array = is_selected_tag($tag, $GLOBALS["query_array"]) ? query_array_unselecting_tag($tag, $GLOBALS["query_array"]) : query_array_selecting_tag($tag, $GLOBALS["query_array"]);
    set_if_exists($id, $_GET[$_GET["controller"]]);
    set_if_not_set($id, "");
    return path($_GET["action"], $_GET["controller"], $id, $binet_prefix, $query_array);
  }

  function is_selected_tag($tag, $query_array) {
    return isset($query_array["tags"]) && in_array($tag, $query_array["tags"]);
  }

  function query_array_selecting_tag($tag, $query_array) {
    $query_array["tags"][] = $tag;
    return $query_array;
  }

  function query_array_unselecting_tag($tag, $query_array) {
    $query_array["tags"] = array_diff($query_array["tags"], array($tag));
    if (is_empty($query_array["tags"])) {
      unset($query_array["tags"]);
    }
    return $query_array;
  }
