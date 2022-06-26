<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\media\Plugin\Field\FieldFormatter\MediaThumbnailFormatter;

/**
 * Plugin implementation of the 'first_media_thumbnail' formatter.
 *
 * @FieldFormatter(
 *   id = "first_media_thumbnail",
 *   label = @Translation("First media thumbnail"),
 *   field_types = {
 *     "entity_reference",
 *   }
 * )
 */
class FirstMediaThumbnail extends MediaThumbnailFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $media_items = $this->getEntitiesToView($items, $langcode);

    // Early opt-out if the field is empty.
    if (empty($media_items)) {
      return $elements;
    }

    $image_style_setting = $this->getSetting('image_style');
    $media_item = $media_items[0];

    /** @var \Drupal\media\MediaInterface $media_item */
    $elements[0] = [
      '#theme' => 'image_formatter',
      '#item' => $media_item->get('thumbnail')->first(),
      '#item_attributes' => [],
      '#image_style' => $this->getSetting('image_style'),
      '#url' => $this->getMediaThumbnailUrl($media_item, $items->getEntity()),
    ];

      // Add cacheability of each item in the field.
      $this->renderer->addCacheableDependency($elements[0], $media_item);


    // Add cacheability of the image style setting.
    if ($this->getSetting('image_link') && ($image_style = $this->imageStyleStorage->load($image_style_setting))) {
      $this->renderer->addCacheableDependency($elements, $image_style);
    }

    return $elements;
  }

}
