<?php

namespace Drupal\autodvig_site\Entity;

use Drupal\autodvig_site\Plugin\Field\FieldType\BuyingPriceItemList;
use Drupal\autodvig_site\Plugin\Field\FieldType\SellingPriceItemList;
use Drupal\autodvig_site\Plugin\Field\FieldType\SpendingsItemList;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Vehicle entity.
 *
 * @ContentEntityType(
 *   id = "vehicle",
 *   label = @Translation("Vehicle"),
 *   bundle_label = @Translation("Vehicle type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\autodvig_site\Entity\VehicleListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\autodvig_site\Form\VehicleForm",
 *       "add" = "Drupal\autodvig_site\Form\VehicleForm",
 *       "edit" = "Drupal\autodvig_site\Form\VehicleForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\autodvig_site\Access\VehicleAccessControlHandler",
 *   },
 *   base_table = "vehicle",
 *   data_table = "vehicle_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer vehicle entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/vehicles/{vehicle}",
 *     "add-page" = "/admin/content/vehicles/add",
 *     "add-form" = "/admin/content/vehicles/add/{vehicle_type}",
 *     "edit-form" = "/admin/content/vehicles/{vehicle}/edit",
 *     "delete-form" = "/admin/content/vehicles/{vehicle}/delete",
 *     "collection" = "/admin/content/vehicles",
 *   },
 *   bundle_entity_type = "vehicle_type",
 *   field_ui_base_route = "entity.vehicle_type.edit_form"
 * )
 */
class Vehicle extends ContentEntityBase implements VehicleInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isAvailable(): bool {
    return $this->get('field_status')->value === 'available';
  }

  /**
   * {@inheritdoc}
   */
  public function getSellingPriceMode(): ?string {
    return $this->get('field_price_mode')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getSellingPrice(): ?string {
    return $this->get('front_selling_price')->value;
  }
  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the vehicle.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the vehicle is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['front_selling_price'] = BaseFieldDefinition::create('string')
      ->setComputed(TRUE)
      ->setClass(SellingPriceItemList::class)
      ->setLabel(t('Front selling price'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('view', [
        'region' => 'hidden',
      ]);

    $fields['spendings'] = BaseFieldDefinition::create('entity_reference')
      ->setComputed(TRUE)
      ->setClass(SpendingsItemList::class)
      ->setLabel(t('Spendings'))
      ->setCardinality(-1)
      ->setRequired(FALSE)
      ->setSetting('target_type', 'spending')
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('view', [
        'region' => 'hidden',
      ]);

    $fields['buying_price'] = BaseFieldDefinition::create('decimal')
      ->setComputed(TRUE)
      ->setClass(BuyingPriceItemList::class)
      ->setLabel(t('Total'))
      ->setCardinality(1)
      ->setRequired(FALSE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('view', [
        'region' => 'hidden',
      ]);

    return $fields;
  }

}
