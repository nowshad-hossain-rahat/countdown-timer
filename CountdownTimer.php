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
      add_shortcode('pd-add-plant-popup', [$this, 'showCountdownTimer']);
    });
  }


  # add fontello css
  function addFontelloCss()
  {
    wp_enqueue_style('fontello', plugin_dir_url(__FILE__) . 'css/fontello.css');
    wp_enqueue_style('fontello-codes', plugin_dir_url(__FILE__) . 'css/fontello-codes.css');
    wp_enqueue_style('fontello-embedded', plugin_dir_url(__FILE__) . 'css/fontello-embedded.css');
  }

  function showCountdownTimer( $attr )
  {

  }


}

if (class_exists('CountdownTimer')) {

  new CountdownTimer();
}




?>
