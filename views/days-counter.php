<div class="nhr-days-counter-container">

  <form class="nhr-days-counter" onsubmit="calculateDaysDifferenceAndShowResult(event)">
    <div class="nhr-dc-form-header">
      <div class="nhr-dc-date-peeker left">
        <label for="nhr-dc-start-date-peeker">Start date</label>
        <input type="date" id="nhr-dc-start-date-peeker" <?php
                                                          if ($event_name !== "End date") {
                                                            echo "readonly='true' value='" . date("Y-m-d") . "'";
                                                          }
                                                          ?> required />
      </div>
      <div class="nhr-dc-date-peeker right">
        <label for="nhr-dc-end-date-peeker"><?php echo $event_name ?></label>
        <input type="date" id="nhr-dc-end-date-peeker" required />
      </div>
    </div>
    <button class="nhr-dc-submit-btn" type="submit">CONTAR</button>
  </form>

  <div class="nhr-days-counter-results">
    <p class="nhr-dc-result" data-result-name="d"><span class="value1">0</span> dia(s)</p>
    <p class="nhr-dc-result" data-result-name="wd"><span class="value1">0</span> semana(s) e <span class="value2">0</span> dia(s)</p>
    <p class="nhr-dc-result" data-result-name="ymd"><span class="value1">0</span> dia(s), <span class="value2">0</span> mes(es) e <span class="value3"></span> ano(s)</p>
  </div>

</div>

<script>
  function calculateDaysDifferenceAndShowResult(event) {
    event.preventDefault()
    let daysCounterContainer = document.querySelector(".nhr-days-counter-container")
    let form = daysCounterContainer.querySelector(".nhr-days-counter")
    let startDate = form.querySelector("#nhr-dc-start-date-peeker").value
    let endDate = form.querySelector("#nhr-dc-end-date-peeker").value
    let diff = calculateDateTimeDifference(startDate, endDate)
    let wdLeft = diff.general.wd_left
    let ymdLeft = diff.general.ymd_left
    let resultElements = document.querySelectorAll(".nhr-days-counter-results .nhr-dc-result")
    resultElements.forEach((resultElm) => {
      let name = resultElm.dataset.resultName
      if (name == 'd') {
        resultElm.querySelector('.value1').innerText = diff.general.days_left
      } else if (name == 'wd') {
        resultElm.querySelector('.value1').innerText = wdLeft.weeks_left
        resultElm.querySelector('.value2').innerText = wdLeft.days_left
      } else if (name == 'ymd') {
        resultElm.querySelector('.value1').innerText = ymdLeft.days_left
        resultElm.querySelector('.value2').innerText = ymdLeft.months_left
        resultElm.querySelector('.value2').innerText = ymdLeft.years_left
      }
    })
  }
</script>
