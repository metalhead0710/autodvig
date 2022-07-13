<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldType;

/**
 * Adds entity reference methods to a computed field item list.
 *
 * In order to implement computed field item list that references entities it's
 * usually easier to just add this trait rather than extending the core item
 * list class of the entity reference, as it adds a lot of unwanted behavior.
 *
 * @see \Drupal\Core\Field\EntityReferenceFieldItemListInterface
 * @see \Drupal\Core\Field\EntityReferenceFieldItemList
 */
trait ComputedEntityReferenceItemListTrait {

  /**
   * Gets the entities referenced by this field, preserving field item deltas.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   An array of entity objects keyed by field item deltas.
   */
  public function referencedEntities() {
    if ($this->isEmpty()) {
      return [];
    }

    // Collect the IDs of existing entities to load, and directly grab the
    // new entities that are already populated in $item->entity.
    $target_entities = $ids = [];
    foreach ($this->list as $delta => $item) {
      if ($item->target_id !== NULL) {
        $ids[$delta] = $item->target_id;
      }
      elseif ($item->hasNewEntity()) {
        $target_entities[$delta] = $item->entity;
      }
    }

    // Load and add the existing entities.
    if ($ids) {
      $target_type = $this->getFieldDefinition()
        ->getSetting('target_type');
      $entities = \Drupal::entityTypeManager()
        ->getStorage($target_type)
        ->loadMultiple($ids);
      foreach ($ids as $delta => $target_id) {
        if (!isset($entities[$target_id])) {
          continue;
        }

        $target_entities[$delta] = $entities[$target_id];
      }

      // Ensure the returned array is ordered by deltas.
      ksort($target_entities);
    }

    return $target_entities;
  }

}
