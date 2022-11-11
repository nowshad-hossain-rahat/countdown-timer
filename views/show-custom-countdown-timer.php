<div class="nhr-countdown-timer-container">
  <div class="nhr-countdown-timer-creator">
    <h3 class="nhr-title">
      Insira a data e hora de destino para criar a contagem regressiva:
    </h3>
    <form onsubmit="createNhrCountdownTimer(event)" class="nhr-countdown-timer-creator-form">
      <ul class="nhr-countdown-timer-creator-inputs">

        <li class="nhr-ctci">
          <label for="nhr-ctci-day">Dia</label>
          <input value="<?php echo date('d') ?>" type="number" id="nhr-ctci-day" maxlength="2" min="1" max="30" required />
        </li>
        <li class="nhr-ctci">
          <label for="nhr-ctci-month">Mês</label>
          <input value="<?php echo date('m') ?>" type="number" id="nhr-ctci-month" maxlength="2" min="1" max="12" required />
        </li>
        <li class="nhr-ctci year">
          <label for="nhr-ctci-year">Ano</label>
          <input value="<?php echo date('Y') ?>" type="number" id="nhr-ctci-year" maxlength="4" minlength="4" required />
        </li>

        <li class="nhr-ctci">
          <label for="nhr-ctci-hour">Hora</label>
          <input value="<?php echo date('H') ?>" type="number" id="nhr-ctci-hour" maxlength="2" min="0" max="23" required />
        </li>

        <li class="nhr-ctci-separator">:</li>

        <li class="nhr-ctci">
          <label for="nhr-ctci-minute">Min</label>
          <input value="<?php echo date('i') ?>" type="number" id="nhr-ctci-minute" maxlength="2" min="0" max="59" required />
        </li>

        <li class="nhr-ctci-separator">:</li>

        <li class="nhr-ctci">
          <label for="nhr-ctci-second">Seg</label>
          <input value="<?php echo date('s') ?>" type="number" id="nhr-ctci-second" maxlength="2" min="0" max="59" required />
        </li>

      </ul>

      <button type="submit" class="nhr-countdown-timer-btn" id="nhr-countdown-timer-creator-submit-btn">Iniciar</button>

    </form>
  </div>
  <div class="nhr-countdown-timer" style="background: #333;display: none;">

    <ul class="countdown-timer-units">
      <li data-unit="days">
        00
        <span>Dias</span>
      </li>
      <li data-unit="hours">
        00
        <span>Horas</span>
      </li>
      <li data-unit="minutes">
        00
        <span>Minutos</span>
      </li>
      <li data-unit="seconds">
        00
        <span>Segundos</span>
      </li>
    </ul>

    <button onclick="resetNhrCountdownTimer(event)" class="nhr-countdown-timer-btn" id="nhr-countdown-timer-reset-btn">Reset</button>

  </div>
</div>

<script>
  let __nhrCustomCountdownTimerStarted = false
  let __NhrCustomCountdownTimerInterval = -1

  function createNhrCountdownTimer(event) {
    event.preventDefault()

    const containerElm = document.querySelector('.nhr-countdown-timer-container')
    const creatorElm = containerElm.querySelector('.nhr-countdown-timer-creator')
    const timerElm = containerElm.querySelector('.nhr-countdown-timer')

    const form = creatorElm.querySelector('.nhr-countdown-timer-creator-form')
    let day = form.querySelector('#nhr-ctci-day').value
    let month = form.querySelector('#nhr-ctci-month').value
    let year = form.querySelector('#nhr-ctci-year').value
    let hour = form.querySelector('#nhr-ctci-hour').value
    let minute = form.querySelector('#nhr-ctci-minute').value
    let second = form.querySelector('#nhr-ctci-second').value

    __NhrCustomCountdownTimerInterval = setInterval(() => {
      let countdownTimer = document.querySelector(".nhr-countdown-timer-container .nhr-countdown-timer")
      let units = countdownTimer.querySelectorAll(".countdown-timer-units li")

      let diff = calculateDateTimeDifference(new Date(), `${year}-${month}-${day} ${hour}:${minute}:${second}`)

      if (diff) {
        let dhmsLeft = diff.general.dhms_left

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
        creatorElm.style.display = "block"
        timerElm.style.display = "none"
        clearInterval(__NhrCustomCountdownTimerInterval)
      }

    }, 1000)

    creatorElm.style.display = "none"
    timerElm.style.display = "flex"
    __nhrCustomCountdownTimerStarted = true

  }

  function resetNhrCountdownTimer(event) {
    clearInterval(__NhrCustomCountdownTimerInterval)
    const containerElm = document.querySelector('.nhr-countdown-timer-container')
    const creatorElm = containerElm.querySelector('.nhr-countdown-timer-creator')
    const timerElm = containerElm.querySelector('.nhr-countdown-timer')
    let units = timerElm.querySelectorAll(".countdown-timer-units li")
    units.forEach((unit) => {
      unit.firstChild.nodeValue = "00"
    })
    creatorElm.style.display = "block"
    timerElm.style.display = "none"
    __nhrCustomCountdownTimerStarted = false
  }
</script>
