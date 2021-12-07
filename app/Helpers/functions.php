<?php

use App\Models\Setting;

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

if (!function_exists('random_color')) {
  function random_color() {
      $background = array('bg-primary', 'bg-info', 'bg-warning', 'bg-default', 'bg-secondary');

      return $background[array_rand($background, 1)];
  }
}

if (!function_exists('cur_symbol')) {
  function cur_symbol($cur) {
      $code = '';
      switch ($cur) {
        case 'NGN':
          $code = "&#8358;";
          break;

        case 'GHS':
          $code = "&#8373;";
          break;

        default:
          $code = "&#8358;";
          break;
      }
      return $code;
  }
}

if (!function_exists('to_money')) {
  function to_money($value) {
      return number_format($value, 2, '.', $sep = ',');
  }
}

if(!function_exists('get_description')) {
  function get_description($key) {
    $label = 'success';

    switch ($key) {
      case 'sell':
          $label = 'success';
          break;
      case 'buy':
          $label = 'danger';
          break;
      case 'payout':
          $label = 'warning';
          break;
      default:
          # code...
          break;
    }

    return $label;
  }
}

if(!function_exists('get_state')) {
  function get_state($key) {
    $label = 'canceled';

    switch ($key) {
      case 'succeed':
          $label = 'approved';
          break;
      case 'rejected':
          $label = 'canceled';
          break;
      case 'pending':
          $label = 'progress';
          break;
      default:
          # code...
          break;
    }

    return $label;
  }
}

if(!function_exists('get_state_general')) {
  function get_state_general($key) {
    $label = 'success';

    switch ($key) {
      case 'succeed':
          $label = 'success';
          break;
      case 'failed':
          $label = 'danger';
          break;
      case 'pending':
          $label = 'warning';
          break;
      default:
          # code...
          break;
    }

    return $label;
  }
}

if (!function_exists('to_add_folder_name')) {
  function to_add_folder_name(string $link, string $folder = 'gift-cards') {
      return str_contains($link, $folder) ? '' : $folder."/";
  }
}

if (!function_exists('convert_to')) {
  function convert_to(int $amount, string $currency) {
      $rate = config('currency.rate')[$currency];
      return $rate * $amount;
  }
}

if (!function_exists('get_cedis_rate')) {
    function get_cedis_rate() {
        $cedis_rate = Setting::where('key', 'cedis_to_naira')->first();
        return floatval($cedis_rate->value);
    }
  }
