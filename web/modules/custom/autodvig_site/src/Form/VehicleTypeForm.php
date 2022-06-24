<?php

namespace Drupal\autodvig_site\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class VehicleTypeForm.
 */
class VehicleTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $vehicle_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $vehicle_type->label(),
      '#description' => $this->t("Label for the Vehicle type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $vehicle_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\autodvig_site\Entity\VehicleType::load',
      ],
      '#disabled' => !$vehicle_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $vehicle_type = $this->entity;
    $status = $vehicle_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label vehicle type.', [
          '%label' => $vehicle_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label vehicle type.', [
          '%label' => $vehicle_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($vehicle_type->toUrl('collection'));
  }

}
