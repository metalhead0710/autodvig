<?php

/**
 * @file
 * The Views hook implementations.
 */

function autodvig_site_views_data_alter(array &$data) {
  $data['search_api_index_spendings']['operations'] = array(
    'title' => t('Operations links'),
    'field' => array(
      'title' => t('Operations links'),
      'help' => t('Provides links to perform entity operations.'),
      'id' => 'entity_operations',
    ),
  );
  $data['search_api_index_clients']['operations'] = array(
    'title' => t('Operations links'),
    'field' => array(
      'title' => t('Operations links'),
      'help' => t('Provides links to perform entity operations.'),
      'id' => 'entity_operations',
    ),
  );

  $data['vehicle']['vehicle_buying_price_total'] = [
    'title' => t('Vehicle buying price total'),
    'help' => t('Calculates total buying price'),
    'field' => [
      'id' => 'vehicle_buying_price_total',
    ],
  ];
}
