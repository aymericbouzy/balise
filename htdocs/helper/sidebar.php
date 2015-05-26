<?php

  function side_link($link, $active = false) {
    return "<li".($active ? " class=\"active\"" : "").">".$link."</li>";
  }
