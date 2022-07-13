<?php

namespace Drupal\autodvig_site\Plugin\autodvig_site\ComputedFieldLoader;

use Drupal\autodvig_site\Api\CurrencyCourseRetrieverInterface;
use Drupal\autodvig_site\Entity\VehicleInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\autodvig_site\Entity\ComputedField\ComputedFieldLoaderBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides loader of the selling price computed field.
 *
 * @Plugin(
 *   id = Drupal\autodvig_site\Plugin\autodvig_site\ComputedFieldLoader\SellingPriceLoader::PLUGIN_ID,
 *   title = @Translation("Selling price"),
 *   description = @Translation("Calculates selling price in dollars.")
 * )
 */
class SellingPriceLoader extends ComputedFieldLoaderBase implements ContainerFactoryPluginInterface {

  /**
   * The plugin ID, for references.
   */
  public const PLUGIN_ID = 'selling_price';

  /**
   * The field name to set the value to.
   */
  protected const TARGET_FIELD_NAME = 'front_selling_price';

  /**
   * Currency retriever.
   *
   * @var \Drupal\autodvig_site\Api\CurrencyCourseRetrieverInterface
   */
  protected CurrencyCourseRetrieverInterface $courseRetriever;

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $pluginId,
    $pluginDefinition
  ) {
    $instance = new static($configuration, $pluginId, $pluginDefinition);
    $instance->courseRetriever = $container->get('autodvig_site.currency_cource_retriever');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function addSourceData(array $entities): void {
    // Do nothing here.
  }

  /**
   * {@inheritdoc}
   */
  protected function loadData(): void {
    // Do nothing here.
  }

  /**
   * {@inheritdoc}
   */
  public function applyResultData(array $entities): void {
    foreach ($entities as $entity) {
      $sellingPrice = $this->getSellingPrice($entity);
      if ($sellingPrice === NULL) {
        continue;
      }

      $entity->set(static::TARGET_FIELD_NAME, $sellingPrice);
    }
  }

  /**
   * Builds front selling price.
   *
   * @return string
   *   Front selling price formatted.
   */
  protected function getSellingPrice(VehicleInterface $entity): ?string {
    $priceMode = $entity->getSellingPriceMode();
    $price = 0;
    switch ($priceMode) {
      case 'auto':
        $course = $this->courseRetriever->get();
        if ($course === NULL) {
          return NULL;
        }
        $eurSellingPrice = $entity->get('field_selling_price_eur')->value;
        $price = $eurSellingPrice * $course;
        break;

      case 'manual':
        $price = $entity->get('field_selling_price_dollar')->value;
        break;
    }

    return number_format($price, 2, '.', ' ');
  }

}
