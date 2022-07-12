<?php

namespace Drupal\autodvig_site\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Spending entities.
 *
 * @ingroup autodvig_site
 */
interface SpendingInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

}
