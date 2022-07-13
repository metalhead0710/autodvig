<?php

namespace Drupal\autodvig_site\Plugin\autodvig_site\ComputedFieldLoader;

use Drupal\autodvig_site\Entity\ComputedField\ComputedFieldLoaderBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides loader of the spendings computed field.
 *
 * @Plugin(
 *   id = \Drupal\autodvig_site\Plugin\autodvig_site\ComputedFieldLoader\SpendingsLoader::PLUGIN_ID,
 *   title = @Translation("Selling price"),
 *   description = @Translation("Calculates selling price in dollars.")
 * )
 */
class SpendingsLoader extends ComputedFieldLoaderBase implements ContainerFactoryPluginInterface {

  /**
   * The plugin ID, for references.
   */
  public const PLUGIN_ID = 'spendings';

  /**
   * The field name to set the value to.
   */
  protected const TARGET_FIELD_NAME = 'spendings';

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The source data array.
   *
   * @var array
   */
  protected array $source = [];

  /**
   * The target data array.
   *
   * @var array
   */
  protected array $target = [];

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $pluginId,
    $pluginDefinition
  ) {
    $instance = new static($configuration, $pluginId, $pluginDefinition);
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function addSourceData(array $entities): void {
    foreach ($entities as $entity) {
      $this->source[$entity->id()] = $entity;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function loadData(): void {
    $vehicleIds = array_keys($this->source);
    if (empty($vehicleIds)) {
      return;
    }
    $storage = $this->entityTypeManager->getStorage('spending');
    $spendingIds = $storage
      ->getQuery()
      ->accessCheck(FALSE)
      ->condition('field_vehicle', $vehicleIds, 'IN')
      ->execute();
    if (empty($spendingIds)) {
      return;
    }

    $spendings = $storage->loadMultiple($spendingIds);
    foreach ($spendings as $spending) {
      $vehicleId = $spending->get('field_vehicle')->target_id;
      $this->target[$vehicleId][] = $spending;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function applyResultData(array $entities): void {
    foreach ($entities as $entity) {
      $spendings = $this->target[$entity->id()] ?? NULL;

      $entity->set(static::TARGET_FIELD_NAME, $spendings);
    }
  }

}
