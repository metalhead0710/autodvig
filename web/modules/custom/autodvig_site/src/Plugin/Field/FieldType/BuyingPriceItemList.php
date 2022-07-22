<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Calculates buing price total.
 */
class BuyingPriceItemList extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    $entity = $this->getEntity();
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

    $this->list[0] = $this->createItem(0, $total);
  }

}
