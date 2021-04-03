<?php

if (!function_exists('to_decimal')) {
  function to_decimal($value) {
      $value /= 100;
      return number_format($value, 2, '.', $sep = '');
  }
}

if (!function_exists('to_naira')) {
  function to_naira($value) {
      $value /= 100;
      $check = ($value - floor($value))  * 100;
      if ($check > 0)
          return number_format($value, 2, '.', $sep = ',');
      else
          return number_format($value, 0, '.', $sep = ',');
  }
}