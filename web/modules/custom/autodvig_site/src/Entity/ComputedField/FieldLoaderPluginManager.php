<?php

namespace Drupal\autodvig_site\Entity\ComputedField;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * The plugin manager of a computed field loaders.
 */
class FieldLoaderPluginManager extends DefaultPluginManager {

  /**
   * The plugin sub-folder in the module.
   */
  public const SUBDIR = 'Plugin/autodvig_site/ComputedFieldLoader';

  /**
   * The plugin annotation class name.
   */
  public const PLUGIN_ANNOTATION_NAME = Plugin::class;

  /**
   * The plugin interface.
   */
  public const PLUGIN_INTERFACE = FieldLoaderInterface::class;

  /**
   * The cache key prefix.
   */
  public const CACHE_KEY_PREFIX = 'computed_field_loader';

  /**
   * A constructor.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler
  ) {
    parent::__construct(
      static::SUBDIR,
      $namespaces,
      $module_handler,
      static::PLUGIN_INTERFACE,
      static::PLUGIN_ANNOTATION_NAME
    );

    $this->setCacheBackend($cache_backend, static::CACHE_KEY_PREFIX);
  }

}
