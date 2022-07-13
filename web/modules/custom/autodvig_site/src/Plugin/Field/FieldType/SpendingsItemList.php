<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldType;

use Drupal\autodvig_site\Entity\ComputedField\LoadableFieldItemListBase;
use Drupal\autodvig_site\Plugin\autodvig_site\ComputedFieldLoader\SpendingsLoader;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;

/**
 * Field item list for the vehicle spendings.
 */
class SpendingsItemList extends LoadableFieldItemListBase implements EntityReferenceFieldItemListInterface {

  use ComputedEntityReferenceItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected const LOADER_PLUGIN_ID = SpendingsLoader::PLUGIN_ID;

}
