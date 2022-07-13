<?php

namespace Drupal\autodvig_site\Entity\ComputedField;

/**
 * Provides an interface of a computed field loader.
 *
 * The loader collects necessary data from the source entities, then loads the
 * field data at once and sets it to the computed field of the passed entities.
 */
interface FieldLoaderInterface {

  /**
   * Extracts source data from passed entities and adds them to loading queue.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface[] $entities
   *   The entities to extract the source data from.
   *
   * @return static
   *   Self.
   */
  public function addSourceEntities(array $entities): FieldLoaderInterface;

  /**
   * Loads the result data using collected source data.
   *
   * @return static
   *   Self.
   */
  public function load(): FieldLoaderInterface;

  /**
   * Returns TRUE if result data has been loaded.
   *
   * Source data can't be added in case the loading already happened.
   *
   * @return bool
   *   TRUE in case the data has been loaded.
   */
  public function isLoaded(): bool;

  /**
   * Sets values of the computed fields on passed entities using loaded data.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface[] $entities
   *   The entities to set the computed values on.
   *
   * @return static
   *   Self.
   */
  public function applyResultsToEntities(array $entities): FieldLoaderInterface;

}
