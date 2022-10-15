<?php

namespace Drupal\timezone\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Class TimezoneConfigureForm.
 *
 * @package Drupal\timezone\Form
 */
class TimezoneConfigureForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'timezone_config_form_id';
  }

 /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {

    return [
        'timezone_config_form_id.settings',
        ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // fetch data using form settings.
    $saved_config = $this->config('timezone_config_form_id.settings');

    //Default config at intial...
    $default_config = \Drupal::config('timezone.settings');

     $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country Name'),
      '#description' => $this->t('Provide Country Name.'),
      '#default_value' => $saved_config->get('country') ?? $default_config->get('country'),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City Name'),
      '#description' => $this->t('Provide City Name.'),
      '#default_value' => $saved_config->get('city') ?? $default_config->get('city'),
    ];

    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Timezone'),
      '#options' => [
          '' => $this->t('Select'),
          'America/Chicago' => $this->t('America/Chicago'),
          'America/New_York' => $this->t('America/New_York'),
          'Asia/Tokyo' => $this->t('Asia/Tokyo'),
          'Asia/Dubai' => $this->t('Asia/Dubai'),
          'Asia/Kolkata' => $this->t('Asia/Kolkata'),
          'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
          'Europe/Oslo' => $this->t('Europe/Oslo'),
          'Europe/London' => $this->t('Europe/London'),
        ],
     '#default_value' => $saved_config->get('timezone') ?? $default_config->get('timezone'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

          $country = $form_state->getValue('country');
          $city = $form_state->getValue('city');
          $timezone = $form_state->getValue('timezone');

          /*
          * Validation for country name, country must be filled with at least 2 length.
          */
          if(empty($country)) {

             $form_state->setErrorByName('country', $this->t('Country cannot be blank.'));

          }elseif(strlen($country) < 2) {

             $form_state->setErrorByName('country', $this->t('Country must be at least 2 characters'));
             }

          /*
          * Validation for city name, city must be filled with at least 2 length.
          */
          if(empty($city)) {

             $form_state->setErrorByName('city', $this->t('City cannot be blank.'));

          }elseif(strlen($city) < 2) {

             $form_state->setErrorByName('city', $this->t('City must be at least 2 characters'));
             }

          /*
          * Validation for Timezone must be selected.
          */
          if(empty($timezone)) {
             $form_state->setErrorByName('timezone', $this->t('Please select Timezone.'));
          }

        parent::validateForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);

    $this->config('timezone_config_form_id.settings')->set('country', $form_state->getValue('country'))->save();
    $this->config('timezone_config_form_id.settings')->set('city', $form_state->getValue('city'))->save();
    $this->config('timezone_config_form_id.settings')->set('timezone', $form_state->getValue('timezone'))->save();
  }

}
