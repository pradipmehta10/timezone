<?php

namespace Drupal\timezone\Services;

use Drupal\Core\Config\ConfigFactory;

/**
 * This class will fetch data from configuratin form.
 */
class GetConfigData {
  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructor to inilized the object.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * Gets my setting.
   */
  public function getData() {
    $config = $this->configFactory->get('timezone_config_form_id.settings');
    return $config;
  }

}
