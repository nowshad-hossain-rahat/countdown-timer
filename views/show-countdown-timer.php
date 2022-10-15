<?php

use Nhrdev\CountdownTimer\DbHandler;

$datetime_diff = DbHandler::calculateDateTimeDifference(
  date("Y-m-d H:i:s"),
  $timer->countdown_till
);

$general_dhms_left = $datetime_diff['general']['dhms_left'];

$_days_left = $general_dhms_left['days_left'];
$_hours_left = $general_dhms_left['hours_left'];
$_minutes_left = $general_dhms_left['minutes_left'];
$_seconds_left = $general_dhms_left['seconds_left'];
$_milliconds_left = $general_dhms_left['milliconds_left'];

?>

<div class="nhr-countdown-timer-container">
  <div class="nhr-countdown-timer" style="background: <?php
                                                      if ($timer->bg_type == 'image') {
                                                        echo 'url(' . $timer->bg_image . ')';
                                                      } else if ($timer->bg_type == 'color') {
                                                        echo $timer->bg_color;
                                                      } else {
                                                        echo 'transparent';
                                                      }
                                                      ?>;">
    <p class="days-left-text">
      <span id="days-value-span" style="font-size: 18px;"> <?php echo $general_dhms_left['days_left']; ?> </span>
      dias para o
      <span style='font-size: 18px;'> <?php echo $timer->timer_name; ?> </span>
      chegar!
    </p>

    <ul class="countdown-timer-units">
      <li data-unit="days">
        <?php echo ($_days_left < 10 ? "0" . $_days_left : $_days_left) ?>
        <span>Dias</span>
      </li>
      <li data-unit="hours">
        <?php echo ($_hours_left < 10 ? "0" . $_hours_left : $_hours_left) ?>
        <span>Horas</span>
      </li>
      <li data-unit="minutes">
        <?php echo ($_minutes_left < 10 ? "0" . $_minutes_left : $_minutes_left) ?>
        <span>Minutos</span>
      </li>
      <li data-unit="seconds">
        <?php echo ($_seconds_left < 10 ? "0" . $_seconds_left : $_seconds_left) ?>
        <span>Segundas</span>
      </li>
    </ul>

  </div>
</div>

<script>
  let CountdownTimer = JSON.parse(`<?php echo json_encode($timer); ?>`)

  window.addEventListener("load", () => {
    const NhrCountdownInterval = setInterval(() => {
      let countdownTimer = document.querySelector(".nhr-countdown-timer-container .nhr-countdown-timer")
      let units = countdownTimer.querySelectorAll(".countdown-timer-units li")

      let diff = calculateDateTimeDifference(new Date(), CountdownTimer.countdown_till)

      if (diff) {
        let dhmsLeft = diff.general.dhms_left

        countdownTimer.querySelector(".days-left-text span#days-value-span").innerText = dhmsLeft.days_left

        units.forEach((unit) => {
          const name = unit.dataset.unit
          if (name == "days") {
            unit.firstChild.nodeValue = dhmsLeft.days_left < 10 ? "0" + dhmsLeft.days_left : dhmsLeft.days_left
          } else if (name == "hours") {
            unit.firstChild.nodeValue = dhmsLeft.hours_left < 10 ? "0" + dhmsLeft.hours_left : dhmsLeft.hours_left
          } else if (name == "minutes") {
            unit.firstChild.nodeValue = dhmsLeft.minutes_left < 10 ? "0" + dhmsLeft.minutes_left : dhmsLeft.minutes_left
          } else if (name == "seconds") {
            unit.firstChild.nodeValue = dhmsLeft.seconds_left < 10 ? "0" + dhmsLeft.seconds_left : dhmsLeft.seconds_left
          }
        })
      } else {
        let countdownTimerContainer = document.querySelector(".nhr-countdown-timer-container")
        let timer = countdownTimerContainer.querySelector(".nhr-countdown-timer")
        if (timer) {
          timer.style.display = "none"
        }
        countdownTimerContainer.insertAdjacentHTML("beforeend", "<h1 class='nhr-countdown-finished'>Countdown Finished!</h1>")
        clearInterval(NhrCountdownInterval)
      }

    }, 1000)
  })
</script>
