<?php

namespace Drupal\autodvig_site\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * The site settings form.
 */
class SiteSettings extends ConfigFormBase {

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
    $form['margin_percent'] = [
      '#type' => 'number',
      '#step' => '.01',
      '#title' => $this->t('Margin percent'),
      '#required' => TRUE,
      '#default_value' => $config->get('margin_percent'),
      "#field_suffix" => '%',
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('autodvig_site.site_settings')
      ->set('margin_percent', $form_state->getValue('margin_percent'))
      ->save();
  }

}
