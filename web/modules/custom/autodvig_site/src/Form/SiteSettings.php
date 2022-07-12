<?php

namespace Drupal\autodvig_site\Form;

use Drupal\Component\Utility\Environment;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\FileInterface;
use Psr\Container\ContainerInterface;

/**
 * The site settings form.
 */
class SiteSettings extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var static $instance */
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'autodvig_site.site_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'autodvig_site';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('autodvig_site.site_settings');

    $form['site_logo'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Site logo'),

      'logo_file' => [
        '#type' => 'managed_file',
        '#title' => $this->t('Site logo'),
        '#default_value' => $config->get('logo_file'),
        '#upload_validators' => [
          'file_validate_extensions' => ['gif png jpg jpeg svg'],
          'file_validate_size' => [Environment::getUploadMaxSize()],
        ],
        '#theme' => 'image_widget',
        '#preview_image_style' => 'medium',
        '#upload_location' => 'public://',
        '#required' => FALSE,
      ],
      'logo_alt' => [
        '#type' => 'textfield',
        '#title' => t('Alternative text'),
        '#description' => t('Short description of the image used by screen readers and displayed when the image is not loaded. This is important for accessibility.'),
        '#default_value' => $config->get('logo_alt'),
        '#maxlength' => 512,
      ],
    ];

    $form['contacts'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Contacts'),

      'phone1' => [
        '#type' => 'textfield',
        '#title' => $this->t('Contact phone 1'),
        '#description' => $this->t('This phone appears on contact blocks'),
        '#maxlength' => 64,
        '#size' => 64,
        '#default_value' => $config->get('phone1'),
      ],
      'phone2' => [
        '#type' => 'textfield',
        '#title' => $this->t('Contact phone 2'),
        '#description' => $this->t('This phone appears on contact blocks'),
        '#maxlength' => 64,
        '#size' => 64,
        '#default_value' => $config->get('phone2'),
      ],
      'visit_us' => [
        '#type' => 'text_format',
        '#title' => $this->t('Visit us'),
        '#format' => 'full_html',
        '#default_value' => $config->get('visit_us')['value'],
      ],
      'map' => [
        '#type' => 'fieldset',
        '#title' => $this->t('Map settings'),

        'lattitude' => [
          '#type' => 'textfield',
          '#title' => $this->t('Lattitude'),
          '#maxlength' => 64,
          '#size' => 64,
          '#default_value' => $config->get('lattitude'),
        ],
        'longtitude' => [
          '#type' => 'textfield',
          '#title' => $this->t('Longtitude'),
          '#maxlength' => 64,
          '#size' => 64,
          '#default_value' => $config->get('longtitude'),
        ],
        'marker_label' => [
          '#type' => 'textfield',
          '#title' => $this->t('Marker label'),
          '#maxlength' => 64,
          '#size' => 64,
          '#default_value' => $config->get('marker_label'),
        ],
      ],
    ];

    $form['socials'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Socials'),

      'facebook' => [
        '#type' => 'textfield',
        '#title' => $this->t('Facebook'),
        '#maxlength' => 255,
        '#size' => 64,
        '#default_value' => $config->get('facebook'),
      ],
      'youtube' => [
        '#type' => 'textfield',
        '#title' => $this->t('Youtube'),
        '#maxlength' => 255,
        '#size' => 64,
        '#default_value' => $config->get('youtube'),
      ],
      'instagram' => [
        '#type' => 'textfield',
        '#title' => $this->t('Instagram'),
        '#maxlength' => 255,
        '#size' => 64,
        '#default_value' => $config->get('instagram'),
      ],
      'telegram' => [
        '#type' => 'textfield',
        '#title' => $this->t('Telegram'),
        '#maxlength' => 255,
        '#size' => 64,
        '#default_value' => $config->get('telegram'),
      ],
    ];

    // Rem this because it is not needed for now.
/*    $form['prices'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Price settings'),

      'margin_percent' => [
        '#type' => 'number',
        '#step' => '.01',
        '#title' => $this->t('Margin percent'),
        '#required' => TRUE,
        '#default_value' => $config->get('margin_percent'),
        "#field_suffix" => '%',
      ],
    ];*/

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $fileId = $form_state->getValue('logo_file')[0] ?? NULL;
    if ($fileId) {
      $file = $this->entityTypeManager->getStorage('file')
        ->load($fileId);
      if ($file instanceof FileInterface) {
        $file->setPermanent();
        $file->save();
      }
    }

    $this->config('autodvig_site.site_settings')
      ->set('logo_file', $form_state->getValue('logo_file'))
      ->set('logo_alt', $form_state->getValue('logo_alt'))
      ->set('phone1', $form_state->getValue('phone1'))
      ->set('phone2', $form_state->getValue('phone2'))
      ->set('visit_us', $form_state->getValue('visit_us'))
      ->set('lattitude', $form_state->getValue('lattitude'))
      ->set('longtitude', $form_state->getValue('longtitude'))
      ->set('marker_label', $form_state->getValue('marker_label'))
      ->set('facebook', $form_state->getValue('facebook'))
      ->set('youtube', $form_state->getValue('youtube'))
      ->set('instagram', $form_state->getValue('instagram'))
      ->set('telegram', $form_state->getValue('telegram'))
      /*->set('margin_percent', $form_state->getValue('margin_percent'))*/
      ->save();
  }

}
