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
    $binet = select_binet($binet, array("id"));
    return link_to(path("show", "binet", $binet["id"]), pretty_binet_no_link($binet["id"]));
  }

  function pretty_binet_no_link($binet) {
    // TODO : create Kès logo
    $binet = select_binet($binet, array("id", "name", "clean-name", "subsidy_provider"));
    return $binet["id"] == KES_ID ? "Kès" : $binet["name"].($binet["subsidy_provider"] == 1 ? "<span class=\"label\">s</span>" : "");
  }

  function pretty_binet_term($binet_term, $link = true) {
    $binet_term = select_term_binet($binet_term, array("binet", "term"));
    $binet = select_binet($binet_term["binet"], array("id", "name"));
    $caption = $binet["name"]." <span style=\"font-size:smaller\" class=\"binet-term\">".$binet_term["term"]."</span>";
    if ($link) {
      return link_to(path("show", "binet", $binet["id"]), $caption);
    } else {
      return $caption;
    }
  }

  function pretty_budget($budget) {
    $budget = select_budget($budget, array("id", "label"));
    return $budget["label"].pretty_tags(select_tags_budget($budget["id"]));
  }

  function pretty_wave($wave, $link = true) {
    // TODO
    return $wave;
  }

  function pretty_student($student, $link = true) {
    $student = select_student($student, array("name", "email"));
    if ($link) {
      return link_to("mailto:".$student["name"]." <".$student["email"].">", $student["name"]);
    } else {
      return $student["name"];
    }
  }

  function pretty_date($date) {
    // TODO
    return $date;
  }

  function pretty_operation_type($type) {
    // TODO
    return $type;
  }

  function pretty_operation($operation, $link = false) {
    // TODO
    $operation = select_operation($operation, array("binet", "term", "id"));
    return link_to(path("show", "operation", $operation["id"], binet_prefix($operation["term"], $operation["id"])), "operation ".$operation["id"]);
  }

  function pretty_request($request) {
    // TODO
    return "request ".$request;
  }

  function pretty_subsidy($subsidy) {
    return "subsidy ".$subsidy;
  }
