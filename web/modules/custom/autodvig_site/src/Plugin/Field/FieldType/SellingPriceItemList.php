<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Field item list for the full user name computed field.
 *
 * It joins name parts using space.
 */
class SellingPriceItemList extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * Builds front selling price.
   *
   * @return string
   *   Front selling price formatted.
   */
  protected function getSellingPrice(): ?string {
    /** @var \Drupal\autodvig_site\Entity\VehicleInterface $entity */
    $entity = $this->getEntity();
    $priceMode = $entity->getSellingPriceMode();
    $price = 0;
    switch ($priceMode) {
      case 'auto':
        $courseRetriever = \Drupal::service('autodvig_site.currency_cource_retriever');
        $course = $courseRetriever->get();
        if ($course === NULL) {
          return NULL;
        }
        $eurSellingPrice = $entity->get('field_selling_price_eur')->value;
        $price = $eurSellingPrice * $course;
        break;

      case 'manual':
        $price = $entity->get('field_selling_price_dollar')->value;
        break;
    }

    return number_format($price, 2, '.', ' ');
  }

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    $value = $this->getSellingPrice();
    if (empty($value)) {
      return;
    }
    $this->list[0] = $this->createItem(0, $value);
  }

}
