<?php

namespace Drupal\autodvig_site\Access;

use Drupal\autodvig_site\Entity\SpendingInterface;
use Drupal\autodvig_site\Entity\VehicleInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Spending entity.
 *
 * @see \Drupal\autodvig_site\Entity\Spending.
 */
class SpendingAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\autodvig_site\Entity\SpendingInterface $entity */

    switch ($operation) {

      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view spendings');

      case 'update':
        if ($this->isOwner($entity, $account)) {
          return AccessResult::allowedIfHasPermission($account, 'manage own spendings');
        }

        return AccessResult::allowedIfHasPermissions($account, ['edit spendings', 'administer spendings'], 'OR');

      case 'delete':
        if ($this->isOwner($entity, $account)) {
          return AccessResult::allowedIfHasPermission($account, 'manage own spendings');
        }

        return AccessResult::allowedIfHasPermissions($account, ['delete spendings', 'administer spendings'], 'OR');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * Checks is passed user is owner of the entity
   * @param \Drupal\autodvig_site\Entity\SpendingInterface $entity
   *   The entity.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user.
   *
   * @return bool
   *   The result of the check.
   */
  protected function isOwner(SpendingInterface $entity, AccountInterface $account): bool {
    return $entity->getOwnerId() === $account->id();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add spendings');
  }


}
