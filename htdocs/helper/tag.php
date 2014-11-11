<?php

  function search_by_tag_path($tag) {
    if (is_selected_tag($tag)) {
      return $_SERVER["path"]
    } else {

    }
  }

  function is_selected_tag($tag, $query_array) {
    return isset($query_array["select_tags"]) && in_array($tag, array($query_array["select_tags"]));
  }

  function query_array_selecting_tag($tag, $query_array) {

  }

  function query_array_unselecting_tag($tag, $query_array) {

  }
