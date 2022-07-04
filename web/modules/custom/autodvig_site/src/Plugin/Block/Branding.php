<?php

namespace Drupal\autodvig_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\file\FileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Branding' block.
 *
 * @Block(
 *  id = "autodvig_branding",
 *  admin_label = @Translation("Branding"),
 * )
 */
class Branding extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Config\ConfigManagerInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $configManager;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->configManager = $container->get('config.manager');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configManager->getConfigFactory()
      ->get('autodvig_site.site_settings');

    return [
      '#theme' => 'autodvig_branding',
      '#logo_url' => $this->getLogoUrl($config),
      '#logo_alt' => $config->get('logo_alt'),
      '#cache' => [
        'tags' => ['config:autodvig_site.site_settings'],
      ],
    ];
  }

  /**
   * Loads file from config and gets its url.
   *
   * @param \Drupal\Core\Config\ImmutableConfig $config
   *   The config.
   *
   * @return string|null
   *   The file url.
   */
  protected function getLogoUrl(ImmutableConfig $config): ?string {
    $logoFid = $config->get('logo_file');
    if (!is_array($logoFid)) {
      return NULL;
    }

    $logoFid = reset($logoFid);
    if (!$logoFid) {
      return NULL;
    }
    $file = $this->entityTypeManager->getStorage('file')
      ->load($logoFid);
    if (!$file instanceof FileInterface) {
      return NULL;
    }

    return $file->createFileUrl();
  }

}
