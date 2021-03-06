<?php

namespace Drupal\autodvig_site\Access;

use Drupal\autodvig_site\Entity\VehicleInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Vehicle entity.
 *
 * @see \Drupal\autodvig_site\Entity\Vehicle.
 */
class VehicleAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\autodvig_site\Entity\VehicleInterface $entity */

    switch ($operation) {

      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished vehicle entities');
        }

        return AccessResult::allowedIfHasPermission($account, 'view published vehicle entities');

      case 'update':
        if ($this->isOwner($entity, $account)) {
          return AccessResult::allowedIfHasPermission($account, 'manage own vehicles');
        }

        return AccessResult::allowedIfHasPermission($account, 'edit vehicle entities');

      case 'delete':
        if ($this->isOwner($entity, $account)) {
          return AccessResult::allowedIfHasPermission($account, 'manage own vehicles');
        }

        return AccessResult::allowedIfHasPermission($account, 'delete vehicle entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * Checks is passed user is owner of the entity
   * @param \Drupal\autodvig_site\Entity\VehicleInterface $entity
   *   The entity.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user.
   *
   * @return bool
   *   The result of the check.
   */
  protected function isOwner(VehicleInterface $entity, AccountInterface $account): bool {
    return $entity->getOwnerId() === $account->id();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add vehicle entities');
  }


}
