<?php

  function pretty_amount($amount) {
    return ($amount > 0 ? "+" : 0).($amount / 100);
  }

  function pretty_tags($tags, $link = false) {
    $tag_string = "";
    foreach ($tags as $tag) {
      $tag = select_tag($tag["id"], array("name", "id"));
      $label = "<span class=\"label".(is_selected($tag["id"], $GLOBALS["query_array"]) ? " tag-selected" : "")."\">".$tag["name"]."</span>";
      if ($link) {
        return link_to(search_by_tag_path($tag["id"]), $label);
      } else {
        return $label;
      }
    }
  }
