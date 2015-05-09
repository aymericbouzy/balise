<?php

  function pretty_amount($amount, $sign = true, $symbol = false) {
    $amount *= 1/100;
    $symbol = $symbol ? "<i class=\"fa fa-fw fa-euro\"></i>" : "";
    if ($sign) {
      return ($amount > 0 ? "+" : "").$amount.$symbol;
    } else {
      return ($amount > 0 ? $amount : -$amount).$symbol;
    }
  }

  function pretty_tags($tags, $link = false) {
    $tag_string = "";
    foreach ($tags as $tag) {
      $label = pretty_tag($tag["id"]);
      if ($link) {
        $tag_string .= link_to(search_by_tag_path($tag["id"]), $label, array("goto" => true, "style" => $link ? "cursor:default" : "", "class" => tag_is_selected($tag["id"], $GLOBALS["query_array"]) ? " tag-selected" : ""));
      } else {
        $tag_string .= $label;
      }
    }
    return $tag_string;
  }

  function pretty_tag($tag) {
    $tag = select_tag($tag, array("name"));
    return "<span class=\"tag-blue\">".$tag["name"]."</span>\t";
  }

  function pretty_binet($binet, $link = true, $special = true) {
    $binet = select_binet($binet, array("id", "name", "clean-name", "subsidy_provider"));
    $caption = $binet["id"] == KES_ID && $special ? "<i class=\"icon-logo-kes fa-2x\" alt=\"Kès\"></i>" : $binet["name"].($special && $binet["subsidy_provider"] == 1 ? " <span class=\"label stamp\">s</span>" : "");
    return $link ? link_to(path("show", "binet", $binet["id"]), $caption) : $caption;
  }

  function pretty_binet_term($binet_term, $link = true, $kes_special = true) {
    $binet_term = select_term_binet($binet_term, array("binet", "term"));
    $caption = pretty_binet($binet_term["binet"], false, $kes_special)." ".($kes_special ? "<span style=\"font-size:smaller\" class=\"binet-term\">".$binet_term["term"]."</span>" : $binet_term["term"]);
    if ($link) {
      return link_to(path("show", "binet", $binet_term["binet"]), $caption);
    } else {
      return $caption;
    }
  }

  function pretty_budget($budget , $link = true, $show_tags = true) {
    $budget = select_budget($budget, array("id", "label","binet","term"));
    $label = $link ? link_to(path("show", "budget", $budget["id"], binet_prefix($budget["binet"], $budget["term"])),$budget["label"]) : $budget["label"];
    return $show_tags? $label." \t".pretty_tags(select_tags_budget($budget["id"])) : $label;
  }

  function pretty_wave($wave, $link = true) {
    $wave = select_wave($wave,array("id", "binet", "submission_date", "term"));
    $binet = select_binet($wave["binet"], array("name"));
    $caption = "Subventions ".$binet["name"]." ".month($wave["submission_date"])." ".year($wave["submission_date"]);
    return $link ? link_to(path("show", "wave", $wave["id"], binet_prefix($wave["binet"], $wave["term"])), $caption) : $caption;
  }

  function pretty_student($student, $link = true, $icon = false) {
    $student = select_student($student, array("name", "id"));
    if ($link) {
      return link_to(path("show", "student", $student["id"]), ( $icon ? "<i class=\"fa fa-user\"></i> " : "").$student["name"]);
    } else {
      return $student["name"];
    }
  }

  function pretty_date($date) {
    return ($date != "0000-00-00") ? strftime("%d/%m/%Y",strtotime($date)) : "" ;
  }

  function pretty_operation_type($type) {
    return "<i class=\"fa fa-".select_operation_type($type, array("icon"))["icon"]."\"></i>";
  }

  function pretty_operation($operation, $link = false, $raw = false) {
    $operation = select_operation($operation, array("binet", "term", "id", "amount", "type", "date","comment"));
    $raw_caption = ($operation["amount"] > 0 ? "(+) " : "(-) ")." ".pretty_date($operation["date"]);
    $raw_caption .= " : ".substr($operation["comment"], 0, 30).(strlen($operation["comment"]) > 30 ? "..." : " ");
    $caption = $raw ? $raw_caption : pretty_operation_type($operation["type"])." ".$raw_caption." (".pretty_amount($operation["amount"], false, true)." )";
    return $link? link_to(path("show", "operation", $operation["id"], binet_prefix($operation["binet"], $operation["term"])), $caption) : $caption;
  }

  function pretty_request($request, $link = true) {
    $request = select_request($request, array("id", "binet", "term"));
    $caption = "Demande de subventions ".pretty_binet_term(term_id($request["binet"], $request["term"]), false);
    return $link ? link_to(path("show", "request", $request["id"], binet_prefix($request["binet"], $request["term"])), $caption) : $caption;
  }

  function pretty_terms_list($binet,$in_list = false) {
    $list = "";
    $li = $in_list ? "<li>" : "";
    $liend = $in_list ? "</li>" : "";
    foreach (select_terms(array("binet" => $binet)) as $binet_term) {
      $binet_term = select_term_binet($binet_term["id"], array("binet", "term"));
      if (has_viewing_rights($binet_term["binet"], $binet_term["term"])) {
        $list .= $li.link_to(path("", "binet", binet_term_id($binet_term["binet"], $binet_term["term"])), $binet_term["term"])." ".$liend;
      } else {
        $list .= $li.$binet_term["term"]." <i> Tu n'as pas la possibilité de voir ce mandat </i>".$liend;
      }
    }
    return $list;
  }
