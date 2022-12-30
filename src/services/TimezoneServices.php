<?php

namespace Drupal\timezone\Services;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Timezone services based on selected configuration.
 */
class TimezoneServices {

  /**
   * Return Array of current day,date,time and complete date in response.
   */
  public function getCurrentTime() {

    $config = \Drupal::config('timezone_config_form_id.settings');

    $timezone = !empty($config->get('timezone')) ? $config->get('timezone') : 'America/New_York';

    $date_time = new DrupalDateTime("now", new \DateTimeZone($timezone));

    $full_date = $date_time->format('dS M Y - h:i A');
    $date = $date_time->format('l, dS M Y');
    $time = $date_time->format('h:i A');

    return [
      'date' => $date,
      'time' => $time,
      'full_date' => $full_date,
    ];

  }

}
