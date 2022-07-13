<?php

namespace Drupal\autodvig_site\Entity\ComputedField;

use Drupal\Core\Plugin\PluginBase;

/**
 * Base class for a loader of a computed field.
 */
abstract class ComputedFieldLoaderBase extends PluginBase implements FieldLoaderInterface {

  /**
   * Whether the loading happened or not.
   *
   * @var bool
   */
  protected $loaded = FALSE;

  /**
   * Loads result data using source data.
   */
  abstract protected function loadData(): void;

  /**
   * Adds source data from entities to the loading queue.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface[] $entities
   *   Source entities.
   */
  abstract protected function addSourceData(array $entities): void;

  /**
   * Applies result data to the passed entities.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface[] $entities
   *   Entities.
   */
  abstract protected function applyResultData(array $entities): void;

  /**
   * {@inheritdoc}
   */
  public function isLoaded(): bool {
    return $this->loaded;
  }

  /**
   * {@inheritdoc}
   */
  public function load(): FieldLoaderInterface {
    if (!$this->isLoaded()) {
      $this->loadData();
      $this->loaded = TRUE;
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addSourceEntities(array $products): FieldLoaderInterface {
    if ($this->isLoaded()) {
      throw new \LogicException(
        "Entities can't be added after loading."
      );
    }

    $this->addSourceData($products);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function applyResultsToEntities(array $entities): FieldLoaderInterface {
    if (!$this->isLoaded()) {
      throw new \LogicException(
        "The loading wasn't triggered."
      );
    }

    $this->applyResultData($entities);
    return $this;
  }

}
