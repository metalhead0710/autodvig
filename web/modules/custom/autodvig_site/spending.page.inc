<?php

/**
 * @file
 * Contains spending.page.inc.
 *
 * Page callback for Spending entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Spending templates.
 *
 * Default template: spending.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_spending(array &$variables) {
  // Fetch Spending Entity Object.
  $spending = $variables['elements']['#spending'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
