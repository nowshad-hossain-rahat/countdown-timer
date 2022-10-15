function calculateDateTimeDifference(starting_date, ending_date) {
  countdown_till = Date.parse(ending_date) / 1000;
  datetime_now = Date.parse(starting_date) / 1000;
  datetime_left = (countdown_till - datetime_now);

  if (datetime_left <= 0) {
    return false
  }

  initial_datetime_left = datetime_left;

  years_left = 0;
  months_left = 0;
  weeks_left = 0;
  days_left = 0;
  hours_left = 0;
  minutes_left = 0;
  seconds_left = 0;

  // calculating years left
  if ((datetime_left / (60 * 60 * 24 * 30 * 12)) >= 1) {
    years_left = Math.floor(datetime_left / (60 * 60 * 24 * 30 * 12));
    datetime_left = datetime_left - (years_left * (60 * 60 * 24 * 30 * 12));
  }

  // calculating months left
  if ((datetime_left / (60 * 60 * 24 * 30)) >= 1) {
    months_left = Math.floor(datetime_left / (60 * 60 * 24 * 30));
    datetime_left = datetime_left - (months_left * (60 * 60 * 24 * 30));
  }

  // calculating weeks left
  if ((datetime_left / (60 * 60 * 24 * 7)) >= 1) {
    weeks_left = Math.floor(datetime_left / (60 * 60 * 24 * 7));
    datetime_left = datetime_left - (weeks_left * (60 * 60 * 24 * 7));
  }

  // calculating days left
  if ((datetime_left / (60 * 60 * 24)) >= 1) {
    days_left = Math.floor(datetime_left / (60 * 60 * 24));
    datetime_left = datetime_left - (days_left * (60 * 60 * 24));
  }

  // calculating hours left
  if ((datetime_left / (60 * 60)) >= 1) {
    hours_left = Math.floor(datetime_left / (60 * 60));
    datetime_left = datetime_left - (hours_left * (60 * 60));
  }

  // calculating minutes left
  if ((datetime_left / 60) >= 1) {
    minutes_left = Math.floor(datetime_left / 60);
    datetime_left = datetime_left - (minutes_left * 60);
  }

  // calculating seconds left
  if (datetime_left >= 1) {
    seconds_left = Math.floor(datetime_left);
  }

  // calculating general weeks and days left
  general_wd_left = {
    weeks_left: 0,
    days_left: 0
  };

  if ((initial_datetime_left / (60 * 60 * 24)) >= 1) {
    total_days_left = Math.ceil(initial_datetime_left / (60 * 60 * 24));
    general_wd_left.weeks_left = Math.floor(total_days_left / 7);
    general_wd_left.days_left = parseInt(total_days_left % 7);
    if (general_wd_left.days_left >= 7) {
      weeks_inside_days = Math.floor(general_wd_left.days_left / 7);
      general_wd_left.weeks_left += weeks_inside_days;
      general_wd_left.days_left = parseInt(general_wd_left.days_left % 7);
    }
  }

  // calculating general years weeks and days left
  general_ymd_left = {
    years_left: years_left,
    months_left: 0,
    days_left: 0
  };

  dt_left = (initial_datetime_left - (general_ymd_left.years_left * (60 * 60 * 24 * 30 * 12)));
  general_ymd_left.months_left = Math.floor(dt_left / (60 * 60 * 24 * 30));
  dt_left = (dt_left - (general_ymd_left.months_left * (60 * 60 * 24 * 30)));
  general_ymd_left.days_left = Math.floor(dt_left / (60 * 60 * 24));

  // calculating general days, hours, minutes and seconds
  general_dhms_left = {
    days_left: 0,
    hours_left: 0,
    minutes_left: 0,
    seconds_left: 0
  };

  general_dhms_left.days_left = Math.floor(initial_datetime_left / (60 * 60 * 24));
  dt_left = (initial_datetime_left - (general_dhms_left.days_left * (60 * 60 * 24)));
  general_dhms_left.hours_left = Math.floor(dt_left / 3600);
  dt_left = (dt_left - (general_dhms_left.hours_left * 3600));
  general_dhms_left.minutes_left = Math.floor(dt_left / 60);
  dt_left = (dt_left - (general_dhms_left.minutes_left * 60));
  general_dhms_left.seconds_left = dt_left;

  return {
    countdown_timer: {
      years_left: years_left,
      months_left: months_left,
      weeks_left: weeks_left,
      days_left: days_left,
      hours_left: hours_left,
      minutes_left: minutes_left,
      seconds_left: seconds_left
    },
    general: {
      years_left: years_left,
      months_left: months_left,
      days_left: Math.ceil(initial_datetime_left / (60 * 60 * 24)),
      wd_left: general_wd_left,
      ymd_left: general_ymd_left,
      dhms_left: general_dhms_left
    }
  };

}
