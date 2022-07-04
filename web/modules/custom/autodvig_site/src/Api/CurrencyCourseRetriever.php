<?php

namespace Drupal\autodvig_site\Api;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use GuzzleHttp\ClientInterface;

/**
 * Gets current cource and sets it to cache.
 */
class CurrencyCourseRetriever implements CurrencyCourseRetrieverInterface {

  /**
   * The cache ID.
   */
  protected const CACHE_ID = 'course';

  const URL_GOVERLA_API = 'https://api.apilayer.com/exchangerates_data/latest?symbols=USD&base=EUR';
  const API_KEY = 'jbf3kX9mI8dVwGUa0xIiUdNkKNf0nwHS';


  /**
   * The cache.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected CacheBackendInterface $cache;

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected ClientInterface $client;

  /**
   * The time service.
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected TimeInterface $time;

  /**
   * A constructor.
   */
  public function __construct(CacheBackendInterface $cache, ClientInterface $client, TimeInterface $time) {
    $this->cache = $cache;
    $this->client = $client;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public function get(): ?float {
    if ($cache = $this->cache->get(static::CACHE_ID)) {
      return $cache->data;
    }

    try {
      $cource = $this->getCourceFromApi();
    }
    catch (\LogicException $e) {
      return NULL;
    }
    $time = $this->time->getRequestTime();
    $expire = $time + 86400;
    $this->cache->set(
      static::CACHE_ID,
      $cource,
      $expire
    );

    return $cource;
  }

  protected function getCourceFromApi(): float {
    $response = $this->client->request(
      'GET',
      static::URL_GOVERLA_API,
      [
        'headers' => [
          'apikey' => static::API_KEY,
        ],
      ]
    );
    $data = json_decode($response->getBody()->getContents());
    $rate = $data->rates->USD ?? NULL;
    if ($rate !== NULL) {
      return $rate;
    }
    throw new \LogicException('There are troubles with API.');
  }



}
