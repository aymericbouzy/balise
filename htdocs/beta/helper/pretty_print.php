<?php

  function pretty_amount($amount, $sign = true, $symbol = false) {
    $amount *= 1/100;
    $symbol = $symbol ? "<i class=\"fa fa-euro\"></i>" : "";
    if ($sign) {
      return ($amount > 0 ? "+" : "").$amount.$symbol;
    } else {
      return ($amount > 0 ? $amount : -$amount).$symbol;
    }
  }

  function pretty_tags($tags, $link = false) {
    $tag_string = "";
    foreach ($tags as $tag) {
      $tag = select_tag($tag["id"], array("name", "id"));
      $label = "<span class=\"tag-blue".(tag_is_selected($tag["id"], $GLOBALS["query_array"]) ? " tag-selected" : "")."\">".$tag["name"]."</span>\t";
      if ($link) {
        $tag_string .= link_to(search_by_tag_path($tag["id"]), $label);
      } else {
        $tag_string .= $label;
      }
    }
    return $tag_string;
  }

  function pretty_binet($binet, $link = true) {
    $binet = select_binet($binet, array("id", "name", "clean-name", "subsidy_provider"));
    $caption = $binet["id"] == KES_ID ? "<i class=\"icon-logo-kes fa-2x\" alt=\"Kès\"></i>" : $binet["name"].($binet["subsidy_provider"] == 1 ? "<span class=\"label\">s</span>" : "");
    return $link ? link_to(path("show", "binet", $binet["id"]), $caption) : $caption;
  }

  function pretty_binet_term($binet_term, $link = true) {
    $binet_term = select_term_binet($binet_term, array("binet", "term"));
    $caption = pretty_binet($binet_term["binet"], false)." <span style=\"font-size:smaller\" class=\"binet-term\">".$binet_term["term"]."</span>";
    if ($link) {
      return link_to(path("show", "binet", $binet_term["binet"]), $caption);
    } else {
      return $caption;
    }
  }

  function pretty_budget($budget , $show_tags = true) {
    $budget = select_budget($budget, array("id", "label"));
    return $show_tags? $budget["label"]." \t".pretty_tags(select_tags_budget($budget["id"])) : $budget["label"];
  }

  function pretty_wave($wave, $link = true) {
    $wave = select_wave($wave,array("id","binet","submission_date"));
    $binet= select_binet($wave["binet"],array("name"));
    $caption = "Subventions ".$binet["name"]." ".month($wave["submission_date"])." ".year($wave["submission_date"]);
    return ($link)? link_to(path("show", "wave", $wave["id"]),$caption) : $caption;
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
    return strftime("%d/%m/%Y",strtotime($date));
  }

  function pretty_operation_type($type) {
    return "<i class=\"fa fa-".select_operation_type($type, array("icon"))["icon"]."\"></i>";
  }

  function pretty_operation($operation, $link = false, $raw = false) {
    $operation = select_operation($operation, array("binet", "term", "id", "amount", "type", "date"));
    $raw_caption = ($operation["amount"] > 0 ? "Recette" : "Dépense")." ".pretty_date($operation["date"]);
    $caption = $raw ? $raw_caption : pretty_operation_type($operation["type"])." ".$raw_caption." (".pretty_amount($operation["amount"], false, true)." )";
    return $link? link_to(path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"])), $caption) : $caption;
  }

  function pretty_request($request, $link = true) {
    $request = select_request($request, array("id", "binet", "term"));
    $caption = "Demande de subventions ".pretty_binet_term($request["binet"]."/".$request["term"], false);
    return $link ? link_to(path("show", "request", $request["id"], binet_prefix($request["binet"], $request["term"])), $caption) : $caption;
  }

  // TODO : get rid of it
  function pretty_subsidy($subsidy) {
    return "subsidy ".$subsidy;
  }

  function pretty_terms_list($binet) {
    $list = "";
    foreach (select_terms(array("binet" => $binet)) as $binet_term) {
      $binet_term = select_term_binet($binet_term["id"], array("binet", "term"));
      $list .= link_to(path("", "binet", binet_term_id($binet_term["binet"], $binet_term["term"])), $binet_term["term"])." ";
    }
    return $list;
  }
