<?php

namespace Drupal\autodvig_site\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Vehicle entities.
 *
 * @ingroup autodvig_site
 */
interface VehicleInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Vehicle name.
   *
   * @return string
   *   Name of the Vehicle.
   */
  public function getName();

  /**
   * Sets the Vehicle name.
   *
   * @param string $name
   *   The Vehicle name.
   *
   * @return \Drupal\autodvig_site\Entity\VehicleInterface
   *   The called Vehicle entity.
   */
  public function setName($name);

  /**
   * Gets the Vehicle creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Vehicle.
   */
  public function getCreatedTime();

  /**
   * Sets the Vehicle creation timestamp.
   *
   * @param int $timestamp
   *   The Vehicle creation timestamp.
   *
   * @return \Drupal\autodvig_site\Entity\VehicleInterface
   *   The called Vehicle entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Checks if vehicle is available.
   *
   * @return bool
   *   The result of the check.
   */
  public function isAvailable(): bool;

  /**
   * Returns selling price mode.
   *
   * @return string|null
   *   The selling price mode if any.
   */
  public function getSellingPriceMode(): ?string;

  /**
   * Returns selling price.
   *
   * @return string|null
   *   The selling price formatted.
   */
  public function getSellingPrice(): ?string;

}
