<?php

namespace Drupal\autodvig_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'FooterBlock' block.
 *
 * @Block(
 *  id = "footer",
 *  admin_label = @Translation("Footer"),
 * )
 */
class Footer extends BlockBase implements ContainerFactoryPluginInterface {

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
    $lattitude = $config->get('lattitude');
    $longtitude = $config->get('longtitude');

    $build = [
      '#theme' => 'autodvig_footer',
      '#phone1' => $config->get('phone1'),
      '#phone2' => $config->get('phone2'),
      '#visit_us' => $config->get('visit_us'),
      '#lattitude' => $lattitude,
      '#longtitude' => $longtitude,
      '#cache' => [
        'tags' => ['config:autodvig_site.site_settings'],
      ],
    ];
    $build['#attached']['library'][] = 'autodvig_site/gmap';
    if (!empty($lattitude) && !empty($longtitude)) {
      $build['#attached']['drupalSettings']['lattitude'] = $lattitude;
      $build['#attached']['drupalSettings']['longtitude'] = $longtitude;
      $build['#attached']['drupalSettings']['api_key'] = getenv('API_KEY');
    }

    return $build;
  }

}
