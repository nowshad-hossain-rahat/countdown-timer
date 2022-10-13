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


  public static function getOneTimerByID(int $timer_id, string $return_type = OBJECT)
  {
    return $GLOBALS['wpdb']->get_row("SELECT * FROM " . self::$tableName . " WHERE timer_id='$timer_id'", $return_type);
  }


  public static function getOneTimerByName(string $timer_name, string $return_type = OBJECT)
  {
    return $GLOBALS['wpdb']->get_row("SELECT * FROM " . self::$tableName . " WHERE BINARY timer_name='$timer_name'", $return_type);
  }


  public static function getAllTimers(string $return_type = OBJECT)
  {
    return $GLOBALS['wpdb']->get_results("SELECT * FROM " . self::$tableName . " ORDER BY timer_id DESC", $return_type);
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

    $exists = self::getOneTimerByName($timer_name);

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


  public static function calculateDateTimeDifference(string $starting_date, string $ending_date)
  {
    $_countdown_till = strtotime($ending_date);
    $_datetime_now = strtotime($starting_date);
    $_datetime_left = ($_countdown_till - $_datetime_now);
    $_initial_datetime_left = $_datetime_left;

    $_years_left = 0;
    $_months_left = 0;
    $_weeks_left = 0;
    $_days_left = 0;
    $_hours_left = 0;
    $_minutes_left = 0;
    $_seconds_left = 0;

    # calculating years left
    if (($_datetime_left / (60 * 60 * 24 * 30 * 12)) >= 1) {
      $_years_left = floor($_datetime_left / (60 * 60 * 24 * 30 * 12));
      $_datetime_left = $_datetime_left - ($_years_left * (60 * 60 * 24 * 30 * 12));
    }

    # calculating months left
    if (($_datetime_left / (60 * 60 * 24 * 30)) >= 1) {
      $_months_left = floor($_datetime_left / (60 * 60 * 24 * 30));
      $_datetime_left = $_datetime_left - ($_months_left * (60 * 60 * 24 * 30));
    }

    # calculating weeks left
    if (($_datetime_left / (60 * 60 * 24 * 7)) >= 1) {
      $_weeks_left = floor($_datetime_left / (60 * 60 * 24 * 7));
      $_datetime_left = $_datetime_left - ($_weeks_left * (60 * 60 * 24 * 7));
    }

    # calculating days left
    if (($_datetime_left / (60 * 60 * 24)) >= 1) {
      $_days_left = floor($_datetime_left / (60 * 60 * 24));
      $_datetime_left = $_datetime_left - ($_days_left * (60 * 60 * 24));
    }

    # calculating hours left
    if (($_datetime_left / (60 * 60)) >= 1) {
      $_hours_left = floor($_datetime_left / (60 * 60));
      $_datetime_left = $_datetime_left - ($_hours_left * (60 * 60));
    }

    # calculating minutes left
    if (($_datetime_left / 60) >= 1) {
      $_minutes_left = floor($_datetime_left / 60);
      $_datetime_left = $_datetime_left - ($_minutes_left * 60);
    }

    # calculating seconds left
    if ($_datetime_left >= 1) {
      $_seconds_left = floor($_datetime_left);
    }

    # calculating general weeks and days left
    $general_wd_left = [
      'weeks_left' => 0,
      'days_left' => 0
    ];

    if (($_initial_datetime_left / (60 * 60 * 24)) >= 1) {
      $total_days_left = ceil($_initial_datetime_left / (60 * 60 * 24));
      $general_wd_left['weeks_left'] = floor($total_days_left / 7);
      $general_wd_left['days_left'] = (int) ($total_days_left % 7);
      if ($general_wd_left['days_left'] >= 7) {
        $_weeks_inside_days = floor($general_wd_left['days_left'] / 7);
        $general_wd_left['weeks_left'] += $_weeks_inside_days;
        $general_wd_left['days_left'] = (int) ($general_wd_left['days_left'] % 7);
      }
    }

    # calculating general years weeks and days left
    $general_ymd_left = [
      'years_left' => $_years_left,
      'months_left' => 0,
      'days_left' => 0
    ];

    $_dt_left = ($_initial_datetime_left - ($general_ymd_left['years_left'] * (60 * 60 * 24 * 30 * 12)));
    $general_ymd_left['months_left'] = floor($_dt_left / (60 * 60 * 24 * 30));
    $_dt_left = ($_dt_left - ($general_ymd_left['months_left'] * (60 * 60 * 24 * 30)));
    $general_ymd_left['days_left'] = floor($_dt_left / (60 * 60 * 24));

    # calculating general days, hours, minutes and seconds
    $general_dhms_left = [
      'days_left' => 0,
      'hours_left' => 0,
      'minutes_left' => 0,
      'seconds_left' => 0
    ];

    $general_dhms_left['days_left'] = floor($_initial_datetime_left / (60 * 60 * 24));
    $_dt_left = ($_initial_datetime_left - ($general_dhms_left['days_left'] * (60 * 60 * 24)));
    $general_dhms_left['hours_left'] = floor($_dt_left / 3600);
    $_dt_left = ($_dt_left - ($general_dhms_left['hours_left'] * 3600));
    $general_dhms_left['minutes_left'] = floor($_dt_left / 60);
    $_dt_left = ($_dt_left - ($general_dhms_left['minutes_left'] * 60));
    $general_dhms_left['seconds_left'] = $_dt_left;

    return [
      'countdown_timer' => [
        'years_left' => $_years_left,
        'months_left' => $_months_left,
        'weeks_left' => $_weeks_left,
        'days_left' => $_days_left,
        'hours_left' => $_hours_left,
        'minutes_left' => $_minutes_left,
        'seconds_left' => $_seconds_left
      ],
      'general' => [
        'years_left' => $_years_left,
        'months_left' => $_months_left,
        'days_left' => ceil($_initial_datetime_left / (60 * 60 * 24)),
        'wd_left' => $general_wd_left,
        'ymd_left' => $general_ymd_left,
        'dhms_left' => $general_dhms_left
      ]
    ];

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
