<?php

    function create_request($wave, $subsidies, $answer = "") {
      $values["wave"] = $wave;
      $values["answer"] = $answer;
      $request = create_entry(
        "request",
        array("wave"),
        array("answer"),
        $values
      );
      foreach($subsidies as $subsidy) {
        create_subsidy($subsidy["budget"], $request, $subsidy["amount"], $subsidy["optionnal_values"]);
      }
      return $request;
    }
