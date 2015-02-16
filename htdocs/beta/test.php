<?php

  include "global/initialisation.php";

  $requests = select_requests(array("state" => "rough_draft"));
  var_dump($requests);
  $request = select_request(2, array("state", "sent"));
  var_dump($request);
