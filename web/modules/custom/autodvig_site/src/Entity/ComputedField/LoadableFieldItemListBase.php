<?php

namespace Drupal\autodvig_site\Entity\ComputedField;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Base class of a computed field that gets populated with a field loader.
 *
 * @see \Drupal\staci_front_api\Entity\ComputedField\FieldLoaderInterface
 */
abstract class LoadableFieldItemListBase extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * Plugin ID of the field loader.
   *
   * Should be overridden by the base class.
   */
  protected const LOADER_PLUGIN_ID = NULL;

  /**
   * Service name of the loader plugin manager.
   */
  public const LOADER_MANAGER_SERVICE = 'plugin.manager.computed_field_loader';

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    $entities = [$this->getEntity()];

    // @todo Find a way to use DI here instead of global service container.
    /** @var \Drupal\Component\Plugin\PluginManagerInterface $manager */
    $manager = \Drupal::service(static::LOADER_MANAGER_SERVICE);
    /** @var \Drupal\staci_front_api\Entity\ComputedField\FieldLoaderInterface $loader */
    $loader = $manager->createInstance(static::LOADER_PLUGIN_ID);

    // In case the value haven't been loaded at this point, it's better to load
    // them one-by-one that returning an empty list.
    $loader->addSourceEntities($entities)
      ->load()
      ->applyResultsToEntities($entities);
  }

}
