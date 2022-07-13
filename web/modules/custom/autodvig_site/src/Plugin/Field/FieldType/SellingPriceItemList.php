<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldType;


use Drupal\autodvig_site\Entity\ComputedField\LoadableFieldItemListBase;
use Drupal\autodvig_site\Plugin\autodvig_site\ComputedFieldLoader\SellingPriceLoader;

/**
 * Field item list for the vehicle selected price.
 */
class SellingPriceItemList extends LoadableFieldItemListBase {

  /**
   * {@inheritdoc}
   */
  protected const LOADER_PLUGIN_ID = SellingPriceLoader::PLUGIN_ID;

}
