<?php

namespace Drupal\autodvig_site\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Vehicle type entity.
 *
 * @ConfigEntityType(
 *   id = "vehicle_type",
 *   label = @Translation("Vehicle type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\autodvig_site\Entity\VehicleTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\autodvig_site\Form\VehicleTypeForm",
 *       "edit" = "Drupal\autodvig_site\Form\VehicleTypeForm",
 *       "delete" = "Drupal\autodvig_site\Form\VehicleTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "vehicle_type",
 *   config_export = {
 *     "id",
 *     "label",
 *   },
 *   admin_permission = "administer site configuration",
 *   bundle_of = "vehicle",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/vehicle_type/{vehicle_type}",
 *     "add-form" = "/admin/structure/vehicle_type/add",
 *     "edit-form" = "/admin/structure/vehicle_type/{vehicle_type}/edit",
 *     "delete-form" = "/admin/structure/vehicle_type/{vehicle_type}/delete",
 *     "collection" = "/admin/structure/vehicle_type"
 *   }
 * )
 */
class VehicleType extends ConfigEntityBundleBase implements VehicleTypeInterface {

  /**
   * The Vehicle type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Vehicle type label.
   *
   * @var string
   */
  protected $label;

}
