<?php

/**
 * @file
 * Contains vehicle.page.inc.
 *
 * Page callback for Vehicle entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Vehicle templates.
 *
 * Default template: vehicle.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_vehicle(array &$variables) {
  // Fetch Vehicle Entity Object.
  $vehicle = $variables['elements']['#vehicle'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
