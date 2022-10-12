<?php

use Nhrdev\CountdownTimer\DbHandler;

  $difference = DbHandler::calculateDateTimeDifference(
    date("Y-m-d H:i:s"),
    // $timer->countdown_till
    "2023-05-01"
  );

  echo "Years left : " . $difference['general']['years_left'] . "<br>";
  echo "Months left : " . $difference['general']['months_left'] . "<br>";
  echo "Weeks and Days left : " . $difference['general']['wd_left']['weeks_left'] . " weeks and " . $difference['general']['wd_left']['days_left'] . " days<br>";
  echo "Days left : " . $difference['general']['days_left'] . "<br>";
  echo $difference['general']['ymd_left']['years_left'] . " year(s) " . $difference['general']['ymd_left']['months_left'] . " month(s) " . $difference['general']['ymd_left']['days_left'] . " day(s) left <br>";

?>

<div class="nhr-countdown-timer-container">
  <div
    class="nhr-countdown-timer"
    style="background: <?php
      if ($timer->bg_type == 'image') { echo 'url(' + $timer->bg_image + ')'; }
      else if ($timer->bg_type == 'color') { echo $timer->bg_color; }
      else { echo 'transparent'; }
    ?>;"
  >

  </div>
</div>
