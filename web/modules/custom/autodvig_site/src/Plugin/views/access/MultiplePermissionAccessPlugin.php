<?php

namespace Drupal\autodvig_site\Plugin\views\access;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Plugin\views\access\Permission;
use Symfony\Component\Routing\Route;

/**
 * Access plugin that provides multiple permission control.
 *
 * @ingroup views_access_plugins
 *
 * @ViewsAccess(
 *   id = "multiple_permissions",
 *   title = @Translation("Multiple permissions"),
 *   help = @Translation("Access will be granted to users with the specified permissions.")
 * )
 */
class MultiplePermissionAccessPlugin extends Permission {

  /**
   * The permission list setting.
   */
  public const PERMISSIONS_SETTING = 'permissions';

  /**
   * The permissions operator setting.
   */
  public const PERMISSIONS_OPERATOR_SETTING = 'operator';

  /**
   * The or operator.
   */
  public const OR_OPERATOR = 'or';

  /**
   * The and operator.
   */
  public const AND_OPERATOR = 'and';

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    $operator = $this->options[static::PERMISSIONS_OPERATOR_SETTING];
    if ($operator === static::OR_OPERATOR) {
      return $this->checkOrAccess($account);
    }

    return $this->checkAndAccess($account);
  }

  /**
   * Checks multilple permissions with OR operator.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account.
   *
   * @return bool
   *   The result of the check.
   */
  protected function checkOrAccess(AccountInterface $account) {
    $permissions = $this->options[static::PERMISSIONS_SETTING];
    foreach ($permissions as $permission) {
      if ($account->hasPermission($permission)) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Checks multilple permissions with AND operator.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account.
   *
   * @return bool
   *   The result of the check.
   */
  protected function checkAndAccess(AccountInterface $account) {
    $permissions = $this->options[static::PERMISSIONS_SETTING];
    foreach ($permissions as $permission) {
      if (!$account->hasPermission($permission)) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function alterRouteDefinition(Route $route) {
    $permissions = $this->options[static::PERMISSIONS_SETTING];
    $operator = $this->options[static::PERMISSIONS_OPERATOR_SETTING];
    if ($operator === static::OR_OPERATOR) {
      $route->setRequirement('_permission', implode('+', $permissions));
      return;
    }

    $route->setRequirement('_permission', implode(',', $permissions));
  }

  /**
   * {@inheritdoc}
   */
  public function summaryTitle() {
    $permissions = $this->permissionHandler->getPermissions();
    $selectedPermissions = $this->options[static::PERMISSIONS_SETTING] ?? NULL;
    if ($selectedPermissions === NULL) {
      return $this->t('Please select permissions and the operator.');
    }
    $permissionTitles = [];
    foreach ($selectedPermissions as $permission) {
      $permissionTitle = $permissions[$permission]['title'] ?? $permission;
      $permissionTitles[] = strip_tags($permissionTitle);
    }
    $operator = $this->options[static::PERMISSIONS_OPERATOR_SETTING];

    return implode(', ', $permissionTitles) . " | $operator";

  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['#pre_render'][] = [static::class, 'preRenderAddFieldsetMarkup'];
    // Get list of permissions.
    $perms = [];
    $permissions = $this->permissionHandler->getPermissions();
    foreach ($permissions as $perm => $perm_item) {
      $provider = $perm_item['provider'];
      $display_name = $this->moduleHandler->getName($provider);
      $perms[$display_name][$perm] = strip_tags($perm_item['title']);
    }

    $form[static::PERMISSIONS_SETTING] = [
      '#type' => 'select',
      '#options' => $perms,
      '#multiple' => TRUE,
      '#title' => $this->t('Permissions'),
      '#default_value' => $this->options[static::PERMISSIONS_SETTING] ?? [],
      '#description' => $this->t('Only users with the selected permissions will be able to access this display.'),
    ];
    $form[static::PERMISSIONS_OPERATOR_SETTING] = [
      '#type' => 'radios',
      '#options' => [
        static::OR_OPERATOR => $this->t('OR'),
        static::AND_OPERATOR => $this->t('AND'),
      ],
      '#title' => $this->t('Operator'),
      '#default_value' => $this->options[static::PERMISSIONS_OPERATOR_SETTING] ?? NULL,
      '#required' => TRUE,
    ];
  }

}
