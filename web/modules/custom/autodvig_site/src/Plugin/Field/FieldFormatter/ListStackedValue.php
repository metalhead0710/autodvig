<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\OptGroup;
use Drupal\options\Plugin\Field\FieldFormatter\OptionsDefaultFormatter;

/**
 * Plugin implementation of the 'list_stacked_value' formatter.
 *
 * @FieldFormatter(
 *   id = "list_stacked_value",
 *   label = @Translation("List stacked value"),
 *   field_types = {
 *     "list_integer",
 *     "list_float",
 *     "list_string",
 *   }
 * )
 */
class ListStackedValue extends OptionsDefaultFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'separator' => NULL,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $formState) {
    $form = parent::settingsForm($form, $formState);
    $form['separator'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Separator'),
      '#default_value' => $this->getSetting('separator'),
      '#placeholder' => 'x',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    $summary[] = $this->t('Separator: @separator', [
      '@separator' => $this->getSetting('separator'),
    ]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    if ($items->isEmpty()) {
      return [];
    }
    $keys = array_keys($items->getValue());
    $lastItemKey = end($keys);

    $provider = $items->getFieldDefinition()
      ->getFieldStorageDefinition()
      ->getOptionsProvider('value', $items->getEntity());
    // Flatten the possible options, to support opt groups.
    $options = OptGroup::flattenOptions($provider->getPossibleOptions());

    $markup = '';
    foreach ($items as $delta => $item) {
      $markup .= $options[$item->value];
      if ($delta !== $lastItemKey) {
        $markup .= $this->getSetting('separator');
      }
    }

    return [
      ['#markup' => $markup],
    ];
  }

}
