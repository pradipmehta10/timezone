<?php

namespace Drupal\timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\timezone\services\TimezoneServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
* TimezoneBlock to fetch location from config form:
*
* @Block(
*  id = "timezone_block",
*  admin_label = @translation("Timezone Block"),
* )
*/

class TimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\timezone\services\TimezoneServices
   */
  protected $timezone;

  /**
   * @param \Drupal\timezone\services\TimezoneServices $current_datetime
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition,TimezoneServices $timezone) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timezone = $timezone;

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
          #$container->get('timezone.getcurrent_time'), // old fashion to Instantiates services.
          $container->get(TimezoneServices::class),
    );

   }

  public function build(){

      /*
      * Load configuration form data:
      */
    $config = \Drupal::config('timezone_config_form_id.settings');

    $country = !empty($config->get('country')) ? $config->get('country') : 'USA';
    $city = !empty($config->get('city')) ? $config->get('city') : 'New York, NY';

    $date_time_data = $this->timezone->getCurrentTime();

    $data = [
    'time' => $date_time_data['time'] ?? '',
    'date' => $date_time_data['date'] ?? '',
    'city' => $city ?? '',
    'country' => $country ?? '',
    ];

  return array(
    '#theme' => 'timezone_display',
    '#data' => $data,
    '#cache' => [
      //'contexts' => ['timezone'],
      //'tags' => ['node_list'],
      'max-age' => 0,
    ],

  );

  }
}
