<?php

  function pretty_amount($amount) {
    return ($amount > 0 ? "+" : 0).($amount / 100);
  }

  function pretty_tags_budget($budget) {

  }

  function pretty_tags($tags, $link) {
    $tag_string = "";
    foreach ($tags as $tag) {
      $tag = select_tag($tag["id"], array("name", "id"));
      ?><span class="label"><?php echo $tag["name"]?></span><?php
    } ?>
  }
