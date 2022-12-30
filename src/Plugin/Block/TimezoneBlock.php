<?php

namespace Drupal\timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\timezone\Services\TimezoneServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\timezone\services\GetConfigData;

/**
 * TimezoneBlock to fetch location from config form.
 *
 * @Block(
 *  id = "timezone_block",
 *  admin_label = @translation("Timezone Block"),
 * )
 */
class TimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Get config data to fetch current configuration.
   *
   * @var Drupal\timezone\services\GetConfigData
   */
  protected $getConfigData;

  /**
   * TimezoneServices to fetch current timezone based on configuration.
   *
   * @var \Drupal\timezone\services\TimezoneServices
   */
  protected $timezone;

  /**
   * Constructor to initialized variable based on parameter passed.
   *
   * @param array $configuration
   *   Block configuration.
   * @param array $plugin_id
   *   Block onfiguration plugin id.
   * @param array $plugin_definition
   *   Block plugin definition.
   * @param Drupal\timezone\services\TimezoneServices $timezone
   *   Timzone services object.
   * @param Drupal\timezone\services\GetConfigData $getConfigData
   *   Config Data services object.
   */
  public function __construct(array $configuration, array $plugin_id, array $plugin_definition, TimezoneServices $timezone, GetConfigData $getConfigData) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timezone = $timezone;
    $this->getConfigData = $getConfigData;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    // Instantiates TimezoneBlock class.
    return new static(
          // Load the service required to construct this class.
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get(TimezoneServices::class),
          $container->get(GetConfigData::class),
    );

  }

  /**
   * To render the template.
   */
  public function build() {

    /*
     * Load configuration form data:
     */
    $config = $this->getConfigData->getData();

    $country = !empty($config->get('country')) ? $config->get('country') : 'USA';
    $city = !empty($config->get('city')) ? $config->get('city') : 'New York, NY';

    $date_time_data = $this->timezone->getCurrentTime();

    $data = [
      'time' => $date_time_data['time'] ?? '',
      'date' => $date_time_data['date'] ?? '',
      'city' => $city ?? '',
      'country' => $country ?? '',
    ];

    return [
      '#theme' => 'timezone_display',
      '#data' => $data,
      '#cache' => [
        'max-age' => 0,
      ],

    ];

  }

}
