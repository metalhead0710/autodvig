<?php

namespace Drupal\autodvig_site\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Provides list of entity reference items with date.
 *
 * @FieldType(
 *   id = "entity_reference_with_date",
 *   label = @Translation("Entity reference with date"),
 *   category = @Translation("Autodvig"),
 *   default_widget = "entity_reference_with_date",
 *   default_formatter = "entity_reference_label",
 *   list_class = "\Drupal\Core\Field\EntityReferenceFieldItemList",
 * )
 */
class EntityReferenceWithDate extends EntityReferenceItem implements DateTimeItemInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(
    FieldStorageDefinitionInterface $field_definition
  ) {
    $properties = parent::propertyDefinitions($field_definition);

    $properties['value'] = DataDefinition::create('datetime_iso8601')
      ->setLabel(t('Date value'))
      ->setRequired(TRUE);

    $properties['date'] = DataDefinition::create('any')
      ->setLabel(t('Computed date'))
      ->setDescription(t('The computed DateTime object.'))
      ->setComputed(TRUE)
      ->setClass('\Drupal\datetime\DateTimeComputed')
      ->setSetting('date source', 'value');

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(
    FieldStorageDefinitionInterface $field_definition
  ) {
    $schema = parent::schema($field_definition);
    $schema['columns'] += [
      'value' => [
        'description' => 'The date value.',
        'type' => 'varchar',
        'length' => 20,
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'quantity_settings' => [],
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function getPreconfiguredOptions() {
    // Provide no preconfigured options to avoid flooding the list of field
    // types.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function onChange($property_name, $notify = TRUE) {
    // Enforce that the computed date is recalculated.
    if ($property_name == 'value') {
      $this->date = NULL;
    }
    parent::onChange($property_name, $notify);
  }

}
