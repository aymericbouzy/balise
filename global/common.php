<?php

  function redirect_to($path) {
    header("Location: ".$SCHEME."://".$HOST."/".$path);
    exit;
  }
