<?php

namespace Drupal\autodvig_site\Access;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Client entity.
 *
 * @see \Drupal\autodvig_site\Entity\Client.
 */
class ClientAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\autodvig_site\Entity\ClientInterface $entity */
    if ($account->hasPermission('administer clients')) {
      return AccessResult::allowed();
    }

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermissions($account, ['manage clients', 'view clients'], 'OR');

      case 'update':
        return AccessResult::allowedIfHasPermissions($account, ['manage clients', 'edit clients'], 'OR');

      case 'delete':
        return AccessResult::allowedIfHasPermissions($account, ['manage clients', 'delete clients'], 'OR');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['manage clients', 'add clients'], 'OR');
  }


}
