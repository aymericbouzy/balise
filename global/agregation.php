<?php

  function sum_array($array, $column) {
    $sum = 0;
    foreach($array as $entry) {
      $sum += $entry[$column];
    }
    return $sum;
  }

  function average_array($array, $column) {
    return sum_array($array, $column) / count($array);
  }
