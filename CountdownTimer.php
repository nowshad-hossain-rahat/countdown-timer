<?php

/*
  Plugin Name: Countdown Timer
  Plugin URI: https://github.com/nowshad-hossain-rahat/
  Author: Nowshad Hossain Rahat
  Author URI: https://github.com/nowshad-hossain-rahat/
  Version: 0.1.0
  Description: This is a custom WP plugin to create and display custom Countdown Timers.
  License: MIT
  Text Dmain: nhr-countdown-timer
*/


if (!defined('ABSPATH')) {
  die;
}


// // including the composer autolaod
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once(dirname(__FILE__) . '/vendor/autoload.php');
}



use Nhrdev\CountdownTimer\AdminPages;
use Nhrdev\CountdownTimer\DbHandler;


final class CountdownTimer
{

  function __construct()
  {

    # setup database
    DbHandler::init();

    # adding admin page
    AdminPages::init();

    # adding action hook to fontello css adding function
    add_action('wp_enqueue_scripts', [$this, 'addFontelloCss']);

    add_action('init', function () {
      $this->createShortcodes([
        'nhr-countdown-timer' => 'showCountdownTimer',
        'nhr-stopwatch' => 'showStopwatch'
      ]);
    });

    $this->checkAndUpdateTimerStatuses();
  }


  # add fontello css
  function addFontelloCss()
  {
    wp_enqueue_style('fontello', plugin_dir_url(__FILE__) . 'css/fontello.css');
    wp_enqueue_style('fontello-codes', plugin_dir_url(__FILE__) . 'css/fontello-codes.css');
    wp_enqueue_style('fontello-embedded', plugin_dir_url(__FILE__) . 'css/fontello-embedded.css');
  }

  # to check and update the statuses of all the timers
  function checkAndUpdateTimerStatuses()
  {
    $timers = DbHandler::getAllTimers();
    foreach ($timers as $timer) {
      $diff = strtotime($timer->countdown_till) - strtotime(date("Y-m-d H:i:s"));
      if ($diff <= 0) {
        DbHandler::updateTimerStatus($timer->timer_id, "stopped");
      }
    }
  }

  function showStopwatch()
  {
    ob_start();

    echo "<style>";
    echo "@font-face {
                    font-family: 'Digital';
                    src: url('" . plugin_dir_url(__FILE__) . "fonts/digital-7.ttf');
                  }";
    require_once plugin_dir_path(__FILE__) . "css/stopwatch.css";
    echo "</style>";
    echo "<script src='" . plugin_dir_url(__FILE__) . "scripts/nhr-stopwatch.js'></script>";
    require_once plugin_dir_path(__FILE__) . "views/stopwatch.php";

    $html = ob_get_clean();

    return $html;
  }

  function showCountdownTimer($attr)
  {
    if (isset($attr["name"])) {
      $timer_name = trim($attr["name"]);

      ob_start();

      $timer = DbHandler::getOneTimerByName($timer_name);

      echo "<style>";
      echo "@font-face {
                    font-family: 'Digital';
                    src: url('" . plugin_dir_url(__FILE__) . "fonts/digital-7.ttf');
                  }";
      require_once plugin_dir_path(__FILE__) . "css/countdown-timer.css";
      echo "</style>";

      if ($timer->status == "counting") {
        echo "<script src='" . plugin_dir_url(__FILE__) . "scripts/functions.js'></script>";
        require_once plugin_dir_path(__FILE__) . "views/show-countdown-timer.php";
      } else {
        echo "<h1 class='nhr-countdown-finished'>Countdown Finished!</h1>";
      }

      $html = ob_get_clean();

      return $html;
    } else {
      return "";
    }
  }

  function createShortcodes(array $shortcodesAndCallbacks)
  {
    foreach ($shortcodesAndCallbacks as $shortcode => $callback) {
      add_shortcode($shortcode, [$this, $callback]);
    }
  }
}

if (class_exists('CountdownTimer')) {
  new CountdownTimer();
}
