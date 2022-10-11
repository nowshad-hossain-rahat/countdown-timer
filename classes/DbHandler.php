<?php

namespace Nhrdev\CountdownTimer;

use Exception;

class DbHandler
{

  public static $tableName = "nhr_countdown_timers";

  public static function init()
  {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // creating table to store readers reward codes
    $sql = "CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
                timer_id INT(255) PRIMARY KEY AUTO_INCREMENT,
                timer_name VARCHAR(255) UNIQUE NOT NULL,
                bg_type ENUM('transparent', 'color', 'image'),
                bg_image TEXT,
                bg_color TEXT,
                countdown_till DATE,
                status ENUM('counting', 'stopped'),
                updated_at DATETIME
            )";

    // executing the sql
    dbDelta($sql);
  }


  public static function searchOneTimer(string $timer_name)
  {
    return $GLOBALS['wpdb']->get_row("SELECT * FROM " . self::$tableName . " WHERE timer_name like %$timer_name%", ARRAY_A);
  }


  public static function getOneTimerByID(int $timer_id)
  {
    return $GLOBALS['wpdb']->get_row("SELECT * FROM " . self::$tableName . " WHERE timer_id='$timer_id'", ARRAY_A);
  }


  public static function getOneTimerByName(int $timer_name)
  {
    return $GLOBALS['wpdb']->get_row("SELECT * FROM " . self::$tableName . " WHERE BINARY timer_name='$timer_name'", ARRAY_A);
  }


  public static function getAllTimers()
  {
    return $GLOBALS['wpdb']->get_results("SELECT * FROM " . self::$tableName . " ORDER BY timer_id DESC", ARRAY_A);
  }


  public static function addNewTimer(string $timer_name, string $countdown_till, string $bg_type, string $bg_image, string $bg_color)
  {

    $exists = self::getOneTimerByName($timer_name);

    if ($exists) {
      return 'exists';
    } else {
      $added = $GLOBALS['wpdb']->insert(
        self::$tableName,
        [
          'timer_name' => $timer_name,
          'countdown_till' => $countdown_till,
          'bg_type' => $bg_type,
          'bg_image' => $bg_image,
          'bg_color' => $bg_color,
          'status' => 'counting',
          'updated_at' => date('Y-m-d H:i:s')
        ],
        [ '%s', '%s', '%s', '%s', '%s', '%s', '%s' ]
      );
      return ($added) ? 'success' : 'error';
    }

  }


  public static function updateTimer(int $timer_id, string $timer_name, string $countdown_till, string $bg_type, string $bg_image, string $bg_color)
  {

    $exists = self::getOneTimerByID($timer_name);

    if ($exists && ($exists['timer_id'] != $timer_id)) {
      return 'exists';
    } else {

      $updated = $GLOBALS['wpdb']->update(
        self::$tableName,
        [
          'timer_name' => $timer_name,
          'countdown_till' => $countdown_till,
          'bg_type' => $bg_type,
          'bg_image' => $bg_image,
          'bg_color' => $bg_color,
          'updated_at' => date('Y-m-d H:i:s')
        ],
        [
          'timer_id' => $timer_id
        ]
      );

      return $updated ? 'success' : 'error';
    }
  }


  public static function updateTimerStatus(int $timer_id, string $status)
  {
    $updated = $GLOBALS['wpdb']->update(
      self::$tableName,
      [
        'status' => $status === 'stopped' ? 'stopped' : 'counting'
      ],
      [
        'timer_id' => $timer_id
      ]
    );
    return $updated ? 'success' : 'error';
  }


  public static function deleteTimer(string $timer_id)
  {
    $deleted = $GLOBALS['wpdb']->delete(
      self::$tableName,
      [
        'timer_id' => $timer_id,
      ]
    );
    return $deleted;
  }


  # to make '2022-01-08' to '08th January
  public static function beautifyDateStr(string $date)
  {

    $months = [
      '01' => 'January',
      '02' => 'February',
      '03' => 'March',
      '04' => 'April',
      '05' => 'May',
      '06' => 'Jun',
      '07' => 'July',
      '08' => 'August',
      '09' => 'September',
      '10' => 'October',
      '11' => 'November',
      '12' => 'December'
    ];

    $dateArr = explode('-', $date);

    $day = $dateArr[2];

    if (str_split($day)[0] == '0') {
      $day = str_replace('0', '', $day);
    }

    $month = $months[$dateArr[1]];
    $year = $dateArr[0];

    if ($day == '1') {
      $day .= 'st';
    } else if ($day == '2') {
      $day .= 'nd';
    } else if ($day == '3') {
      $day .= 'rd';
    } else if ($day == '11' || $day == '12' || $day == '13') {
      $day .= 'th';
    } else if (count(str_split($day)) > 1 && str_split($day)[1] == '1') {
      $day .= 'st';
    } else if (count(str_split($day)) > 1 && str_split($day)[1] == '2') {
      $day .= 'nd';
    } else if (count(str_split($day)) > 1 && str_split($day)[1] == '3') {
      $day .= 'rd';
    } else {
      $day .= 'th';
    }

    return "$day $month, $year";
  }
}
