<?php

namespace Drupal\autodvig_site\Form;

use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Vehicle edit forms.
 *
 * @ingroup autodvig_site
 */
class VehicleForm extends ContentEntityForm {

  /**
   * The config manager.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  /*protected ConfigManagerInterface $configManager;*/

  /**
   * {@inheritdoc}
   */
/*  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    $instance = parent::create($container);
    $instance->configManager = $container->get('config.manager');

    return $instance;
  }*/

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var \Drupal\autodvig_site\Entity\Vehicle $entity */
    $form = parent::buildForm($form, $form_state);

    $form['#attached']['library'][] = 'autodvig_site/buying_price_calculator';

    return $form;
  }

  /**
   * Returns margin percent if set.
   *
   * @return int|null
   *   The margin percent
   */
/*  protected function getMarginPercent(): ?float {
    $config = $this->configManager->getConfigFactory()->get('autodvig_site.site_settings');
    if ($config === NULL) {
      return NULL;
    }

    return $config->get('margin_percent');
  }*/

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Vehicle.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Vehicle.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.vehicle.canonical', ['vehicle' => $entity->id()]);
  }

}
