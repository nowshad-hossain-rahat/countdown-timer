<div class="nhr-stopwatch-container">
  <div class="nhr-stopwatch">
    <ul class="nhr-stopwatch-units">
      <li class="single-unit hours">00</li>
      <li class="separator">:</li>
      <li class="single-unit minutes">00</li>
      <li class="separator">:</li>
      <li class="single-unit seconds">00</li>
      <li class="separator">:</li>
      <li class="single-unit milliseconds">00</li>
    </ul>
  </div>
  <div class="nhr-stopwatch-action-buttons">
    <button class="nhr-btn" id="nhr-reset-stopwatch-btn">Redefinir</button>
    <button class="nhr-btn" id="nhr-start-stop-stopwatch-btn">Começar</button>
  </div>
</div>

<script>
  const nhrStopwatch = new NhrStopwatch({
    hours: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.hours",
    minutes: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.minutes",
    seconds: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.seconds",
    milliseconds: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.milliseconds"
  })

  document
    .querySelector(".nhr-stopwatch-container .nhr-stopwatch-action-buttons #nhr-start-stop-stopwatch-btn")
    .addEventListener("click", (evt) => {
      if (nhrStopwatch.isStopped()) {
        nhrStopwatch.start()
        evt.target.innerText = "Começar"
      } else {
        nhrStopwatch.stop()
        evt.target.innerText = "Pare"
      }
    })

  document
    .querySelector(".nhr-stopwatch-container .nhr-stopwatch-action-buttons #nhr-reset-stopwatch-btn")
    .addEventListener("click", (evt) => {
      nhrStopwatch.reset()
    })
</script>
