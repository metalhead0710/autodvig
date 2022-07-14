<?php

namespace Drupal\autodvig_site\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Client entities.
 *
 * @ingroup autodvig_site
 */
interface ClientInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Client name.
   *
   * @return string
   *   Name of the Client.
   */
  public function getName();

  /**
   * Sets the Client name.
   *
   * @param string $name
   *   The Client name.
   *
   * @return \Drupal\autodvig_site\Entity\ClientInterface
   *   The called Client entity.
   */
  public function setName($name);

  /**
   * Gets the Client creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Client.
   */
  public function getCreatedTime();

  /**
   * Sets the Client creation timestamp.
   *
   * @param int $timestamp
   *   The Client creation timestamp.
   *
   * @return \Drupal\autodvig_site\Entity\ClientInterface
   *   The called Client entity.
   */
  public function setCreatedTime($timestamp);

}
