<?php

namespace Drupal\autodvig_site\Entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines a class to build a listing of Spending entities.
 *
 * @ingroup autodvig_site
 */
class SpendingListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Spending ID');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\autodvig_site\Entity\Spending $entity */
    $row['id'] = $entity->id();

    return $row + parent::buildRow($entity);
  }

}
