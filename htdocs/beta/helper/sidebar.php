<?php

  function li_link($link, $active = false) {
    return "<li".($active ? " class=\"active\"" : "").">".$link."</li>";
  }
