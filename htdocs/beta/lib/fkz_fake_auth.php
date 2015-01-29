<?php

  function frankiz_do_auth() {
    redirect_to_path(path("chose_identity", "home"));
  }

  function frankiz_get_response() {
    return select_student($_GET["student"], array("hruid"));
  }
