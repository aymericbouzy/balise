<?php

  function sum_array($array, $column, $sign = "") {
    $sum = 0;
    foreach($array as $entry) {
      if (empty($sign) || ($sign == "negative" && $entry[$column] < 0) || ($sign == "positive" && $entry[$column] > 0)) {
        $sum += $entry[$column];
      }
    }
    return $sum;
  }

  function average_array($array, $column) {
    return sum_array($array, $column) / count($array);
  }
