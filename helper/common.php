<?php

  function link_to($path, $caption, $class = "") {
    return "<a href=\"".$path."\"".(empty($class) ? "" : " class=\"".$class."\"").">".$caption."</a>";
  }

  function pretty_amount($amount) {
    return ($amount > 0 ? "+" : 0).($amount / 100);
  }

  //TODO img
