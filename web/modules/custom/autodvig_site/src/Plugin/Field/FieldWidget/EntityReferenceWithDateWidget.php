<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides widget for entity reference with date field type.
 *
 * @FieldWidget(
 *   id = "entity_reference_with_date",
 *   label = @Translation("Entity reference with date"),
 *   field_types = {
 *     "entity_reference_with_date"
 *   }
 * )
 */
class EntityReferenceWithDateWidget extends EntityReferenceAutocompleteWidget {

  use StringTranslationTrait;

  /**
   * The date format storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $dateStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->dateStorage = $container->get('entity_type.manager')->getStorage('date_format');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(
    FieldItemListInterface $items,
    $delta,
    array $element,
    array &$form,
    FormStateInterface $form_state
  ) {
    $widget = parent::formElement(
      $items,
      $delta,
      $element,
      $form,
      $form_state
    );

    $widget['value'] = [
      '#type' => 'datetime',
      '#default_value' => NULL,
      '#date_increment' => 1,
      '#date_date_element' => 'date',
      '#date_date_format' => $this->dateStorage->load('html_date')->getPattern(),
      '#date_time_element' => 'none',
      '#date_timezone' => date_default_timezone_get(),
      '#default_value' => $items[$delta]->date ?? NULL,
    ];

    return $widget;
  }

}
