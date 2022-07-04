<?php

namespace Drupal\autodvig_site\Controller;

use Drupal\autodvig_site\Entity\VehicleInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class VehicleViewController.
 */
class VehicleViewController extends ControllerBase {

  /**
   * Route title callback.
   *
   * @param \Drupal\autodvig_site\Entity\VehicleInterface $vehicle
   *   The vehicle.
   *
   * @return array
   *   The vehicle label as a render array.
   */
  public function buildTitle(VehicleInterface $vehicle) {
    return ['#markup' => $vehicle->label(), '#allowed_tags' => Xss::getHtmlTagList()];
  }

}
