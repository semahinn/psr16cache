<?php

namespace Snr\Psr16cache;

/**
 * Создаёт экземпляры файловых кэшей (@see \Snr\Psr16cache\FileCacheInterface)
 */
class FileCacheFactory {

  /**
   * @var string
   */
  protected $factoryId;

  /**
   * @param string $factory_id
   */
  public function __construct(string $factory_id) {
    $this->factoryId = $factory_id;
  }

  /**
   * @param string $collection
   *  Идентификатор экземпляра файлового кэша
   *
   * @param array $default_configuration
   *
   * @return FileCacheInterface
   *  Экземпляр файлового кэша
   */
  public function get(string $collection, array $default_configuration = []) {
    $configuration = [];
    if (!empty($default_configuration)) {
      $configuration += $default_configuration;
    }

    // Psr16FileCache пока едиственная реализация по-умолчанию
    $fallback_configuration = [
      'class' => 'Snr\Psr16cache\Psr16FileCache',
      'collection' => $collection,
      'cache_backend_class' => NULL,
      'cache_backend_configuration' => []
    ];
    $configuration = $configuration + $fallback_configuration;

    $class = $configuration['class'];
    return new $class($configuration['collection'], $configuration['cache_backend_class'], $configuration['cache_backend_configuration']);
  }
}
