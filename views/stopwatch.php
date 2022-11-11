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
  const __nhrStopwatch__ = new NhrStopwatch({
    hours: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.hours",
    minutes: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.minutes",
    seconds: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.seconds",
    milliseconds: ".nhr-stopwatch-container .nhr-stopwatch .nhr-stopwatch-units .single-unit.milliseconds"
  })

  document
    .querySelector(".nhr-stopwatch-container .nhr-stopwatch-action-buttons #nhr-start-stop-stopwatch-btn")
    .addEventListener("click", (evt) => {
      if (__nhrStopwatch__.isStopped()) {
        __nhrStopwatch__.start()
        evt.target.innerText = "Pare"
        evt.target.style.background = "firebrick"
      } else {
        __nhrStopwatch__.stop()
        evt.target.innerText = "Começar"
        evt.target.style.background = "dodgerBlue"
      }
    })

  document
    .querySelector(".nhr-stopwatch-container .nhr-stopwatch-action-buttons #nhr-reset-stopwatch-btn")
    .addEventListener("click", (evt) => {
      const startStopBtn = document.querySelector(".nhr-stopwatch-container .nhr-stopwatch-action-buttons #nhr-start-stop-stopwatch-btn")
      __nhrStopwatch__.reset()
      startStopBtn.innerText = (__nhrStopwatch__.isStopped()) ? "Começar" : "Pare"
      startStopBtn.style.background = (__nhrStopwatch__.isStopped()) ? "dodgerBlue" : "firebrick"
    })
</script>
