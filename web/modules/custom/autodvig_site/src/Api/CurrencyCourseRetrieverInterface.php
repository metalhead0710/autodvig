<?php

namespace Drupal\autodvig_site\Api;

/**
 * Gets current cource and sets it to cache.
 */
interface CurrencyCourseRetrieverInterface {

  /**
   * @return float
   *   Returns the current dollar course related to EUR.
   */
  public function get(): ?float;

}
