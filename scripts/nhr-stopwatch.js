class NhrStopwatch {

  hours = 0
  minutes = 0;
  seconds = 0;
  milliseconds = 0;
  hoursElm = null
  minutesElm = null
  secondsElm = null
  millisecondsElm = null
  stopped = true
  stopwatchInterval = null

  constructor(nhrStopwatchSettings) {

    const { hours, minutes, seconds, milliseconds } = nhrStopwatchSettings

    this.hoursElm = document.querySelector(hours)
    this.minutesElm = document.querySelector(minutes)
    this.secondsElm = document.querySelector(seconds)
    this.millisecondsElm = document.querySelector(milliseconds)

  }

  putValuesToHtml() {
    if (this.hoursElm && this.minutesElm && this.secondsElm && this.millisecondsElm) {
      this.millisecondsElm.innerText = (this.milliseconds < 10 ? "0" + this.milliseconds : this.milliseconds).toString()
      this.secondsElm.innerText = (this.seconds < 10 ? "0" + this.seconds : this.seconds).toString()
      this.minutesElm.innerText = (this.minutes < 10 ? "0" + this.minutes : this.minutes).toString()
      this.hoursElm.innerText = (this.hours < 10 ? "0" + this.hours : this.hours).toString()
    }
  }

  start() {
    this.stopped = false
    this.stopwatchInterval = setInterval(() => {
      if (!this.stopped) {

        this.milliseconds += 1

        if (this.milliseconds == 100) {
          this.seconds += 1
          this.milliseconds = 0
        }

        this.putValuesToHtml()

        if (this.seconds == 60) {
          this.minutes += 1
          this.seconds = 0
        }

        this.putValuesToHtml()

        if (this.minutes == 60) {
          this.hours += 1
          this.minutes = 0
        }

        this.putValuesToHtml()

      }
    }, 10)
  }

  stop() {
    clearInterval(this.stopwatchInterval)
    this.stopped = true
  }

  isStopped() {
    return (this.stopped === true)
  }

  reset() {
    clearInterval(this.stopwatchInterval)
    this.hours = 0
    this.minutes = 0
    this.seconds = 0
    this.milliseconds = 0
    this.stop()
    this.putValuesToHtml()
  }

}
