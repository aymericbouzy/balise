<?php

  $origin_action = "review";
  $destination_action = "send_back";
  $id = $GLOBALS["request"]["id"];
  $form["redirect_to_if_error"] = path($origin_action, "request", $id, binet_prefix(binet, term));
  $form["destination_path"] = path($destination_action, "request", $id, binet_prefix(binet, term));
  $form["html_form_path"] = VIEW_PATH."binet/request/send_back.php";
  $form["fields"]["comment"] = create_text_field("le commentaire", array("optional" => 1));
