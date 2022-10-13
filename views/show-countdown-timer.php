<?php

use Nhrdev\CountdownTimer\DbHandler;

$datetime_diff = DbHandler::calculateDateTimeDifference(
  date("Y-m-d H:i:s"),
  $timer->countdown_till
);

$general_dhms_left = $datetime_diff['general']['dhms_left'];

?>

<div class="nhr-countdown-timer-container">
  <div class="nhr-countdown-timer" style="background: <?php
                                                      if ($timer->bg_type == 'image') {
                                                        echo 'url(' + $timer->bg_image + ')';
                                                      } else if ($timer->bg_type == 'color') {
                                                        echo $timer->bg_color;
                                                      } else {
                                                        echo 'transparent';
                                                      }
                                                      ?>;">
    <p class="days-left-text">
      <span style="font-size: 18px;"> <?php echo $general_dhms_left['days_left']; ?> </span>
       days untill
      <span style='font-size: 18px;'> <?php echo $timer->timer_name; ?> </span>
       arrives!
    </p>

    <ul class="countdown-timer-units">
      <li data-unit="days">
        <?php echo $general_dhms_left['days_left'] ?>
        <span>Days</span>
      </li>
      <li data-unit="hours">
        <?php echo $general_dhms_left['hours_left'] ?>
        <span>Hours</span>
      </li>
      <li data-unit="minutes">
        <?php echo $general_dhms_left['minutes_left'] ?>
        <span>Minutes</span>
      </li>
      <li data-unit="seconds">
        <?php echo $general_dhms_left['seconds_left'] ?>
        <span>Seconds</span>
      </li>
    </ul>

  </div>
</div>

<script>
  const CountdownTimer = JSON.parse(`<?php echo json_encode($timer); ?>`)

  window.addEventListener("load", () => {
    const NhrCountdownInterval = setInterval(() => {
      let countdownTimer = document.querySelector(".nhr-countdown-timer-container .nhr-countdown-timer")
      let units = countdownTimer.querySelectorAll(".countdown-timer-units li")

      let diff = calculateDateTimeDifference(new Date(), CountdownTimer.countdown_till)
      let dhmsLeft = diff.general.dhms_left

      units.forEach((unit) => {
        const name = unit.dataset.unit
        if (name == "days") {
          unit.firstChild.nodeValue = dhmsLeft.days_left
        } else if (name == "hours") {
          unit.firstChild.nodeValue = dhmsLeft.hours_left == 0 ? "00" : dhmsLeft.hours_left
        } else if (name == "minutes") {
          unit.firstChild.nodeValue = dhmsLeft.minutes_left == 0 ? "00" : dhmsLeft.minutes_left
        } else if (name == "seconds") {
          unit.firstChild.nodeValue = dhmsLeft.seconds_left == 0 ? "00" : dhmsLeft.seconds_left
        }
      })

    }, 1000)
  })
</script>
