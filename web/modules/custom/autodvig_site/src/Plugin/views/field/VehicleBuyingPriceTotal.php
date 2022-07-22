<?php

namespace Drupal\autodvig_site\Plugin\views\field;

use Drupal\autodvig_site\Entity\VehicleInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Random;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("vehicle_buying_price_total")
 */
class VehicleBuyingPriceTotal extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['hide_alter_empty'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $row) {
    $entity = $row->_entity;
    if (!$entity instanceof VehicleInterface) {
      return 0;
    }

    $total = 0;

    $europeByingPriceField = $entity->get('field_europe_price');
    if (!$europeByingPriceField->isEmpty()) {
      $total += $europeByingPriceField->value;
    }
    $deliveryPriceField = $entity->get('field_delivery');
    if (!$deliveryPriceField->isEmpty()) {
      $total += $deliveryPriceField->value;
    }
    $customsClearancePriceField = $entity->get('field_customs_clearence');
    if (!$customsClearancePriceField->isEmpty()) {
      $total += $customsClearancePriceField->value;
    }

    if ($total === 0) {
      return $this->t('N/A');
    }

    return number_format($total, '2', '.', ' ') . '€';
  }

}
