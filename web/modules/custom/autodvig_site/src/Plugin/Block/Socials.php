<?php

namespace Drupal\autodvig_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Socials' block.
 *
 * @Block(
 *  id = "autodvig_socials",
 *  admin_label = @Translation("Socials"),
 * )
 */
class Socials extends BlockBase implements ContainerFactoryPluginInterface{

  /**
   * Drupal\Core\Config\ConfigManagerInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $configManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->configManager = $container->get('config.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configManager->getConfigFactory()
      ->get('autodvig_site.site_settings');

    return [
      '#theme' => 'autodvig_socials',
      '#facebook' => $config->get('facebook'),
      '#youtube' => $config->get('youtube'),
      '#instagram' => $config->get('instagram'),
      '#telegram' => $config->get('telegram'),
      '#cache' => [
        'tags' => ['config:autodvig_site.site_settings'],
      ],
    ];
  }

}
