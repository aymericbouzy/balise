<?php

  function pretty_amount($amount) {
    return ($amount > 0 ? "+" : "").($amount / 100);
  }

  function pretty_tags($tags, $link = false) {
    $tag_string = "";
    foreach ($tags as $tag) {
      $tag = select_tag($tag["id"], array("name", "id"));
      $label = "<span class=\"tag-blue".(tag_is_selected($tag["id"], $GLOBALS["query_array"]) ? " tag-selected" : "")."\">".$tag["name"]."</span>";
      if ($link) {
        $tag_string .= link_to(search_by_tag_path($tag["id"]), $label);
      } else {
        $tag_string .= $label;
      }
    }
    return $tag_string;
  }

  function pretty_binet($binet) {
    $binet = select_binet($binet, array("id", "name", "clean-name", "subsidy_provider"));
    $content = $binet["id"] == KES_ID ? "Kès" : $binet["name"].($binet["subsidy_provider"] == 1 ? "<span class=\"label\">s</span>" : "");
    return link_to(path("show", "binet", $binet["id"]), $content);
  }

  function pretty_budget($budget) {
    $budget = select_budget($budget, array("id", "label"));
    return $budget["label"].pretty_tags(select_tags_budget($budget["id"]));
  }

  function pretty_student($student) {
    $student = select_student($student, array("name", "email"));
    return link_to("mailto:".$student["name"]." <".$student["email"].">", $student["name"]);
  }

  function pretty_date($date) {
    // TODO
    return $date;
  }

  function pretty_operation_type($type) {
    // TODO
    return $date;
  }
