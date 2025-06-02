<?php

namespace Snr\Psr16cache;

/**
 * Описывает экземпляр файлового кэша с определённым идентификатором ($collection)
 * Для каждого закэшированного содержимого файла хранит дату последнего изменения,
 * на основе которых происходит актуализация содержимого файла в кэше
 */
abstract class FileCache implements FileCacheInterface {

  /**
   * Идентификатор экземпляра кэша
   *
   * @var string
   */
  protected $collection;

  /**
   * Закэшированные данные
   *
   * @var array
   */
  protected static $results = [];

  // TODO: Логика поиска файлов и помещения их содержимого в кэш
  // может быть реализована несколькими способами
  // Хорошим решением будет её инкапсуляция в некоторую абстракцию, которая
  // и описывает методы помещения/извлечения содержимого
  // (Например, @see Snr\Psr16cache\FileCacheBackendInterface)
  // Этот экземпляр будет доступен через свойство $this->cacheBackend
  /**
   * @var FileCacheBackendInterface
   */
  protected $cacheBackend;

    /**
     * @param string $collection
     *  Идентификатор этого файлового кэша
     *
     * @param string|NULL $cache_backend_class
     *  Класс, реализующий @see FileCacheBackendInterface
     *
     * @param array $cache_backend_configuration
     */
  public function __construct(string $collection, string $cache_backend_class = NULL, array $cache_backend_configuration = []) {
    if (empty($collection)) {
      throw new \InvalidArgumentException(
        'Требуется указать идентификатор для инициализации экземпляра файлового кэша');
    }
    $this->collection = $collection;
    
    if ($cache_backend_class) {
      $this->cacheBackend = new $cache_backend_class($cache_backend_configuration);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function get(string $filepath) {
    $cached = $this->getMultiple([$filepath]);
    return $cached[$filepath] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getMultiple(array $filepaths) {
    $file_data = [];
    $remaining_cids = [];

    foreach ($filepaths as $filepath) {
      if (!file_exists($filepath)) {
        continue;
      }

      $realpath = realpath($filepath);
      if (empty($realpath)) {
        continue;
      }

      $cid = "$this->collection:$realpath";
      if (isset(static::$results[$cid]) && static::$results[$cid]['mtime'] == filemtime($filepath)) {
        $file_data[$filepath] = static::$results[$cid]['data'];
      }
      else {
        // Если файл был изменён - необходимо получить его заново и записать в кэш
        $remaining_cids[$cid] = $filepath;
      }
    }

    // Получим все файлы, содержимое которых было изменено
    // и актуализируем этими данными кэш
    if ($remaining_cids && $this->cacheBackend) {
      $cache_results = $this->cacheBackend->fetch(array_keys($remaining_cids)) ?: [];
      foreach ($cache_results as $cid => $cached) {
        $filepath = $remaining_cids[$cid];
        if ($cached['mtime'] == filemtime($filepath)) {
          $file_data[$cached['filepath']] = $cached['data'];
          static::$results[$cid] = $cached;
        }
      }
    }

    return $file_data;
  }

  /**
   * {@inheritdoc}
   */
  public function set(string $filepath, $data) {
    $realpath = realpath($filepath);
    $cached = [
      'mtime' => filemtime($filepath),
      'filepath' => $filepath,
      'data' => $data,
    ];
    $cid = "$this->collection:$realpath";
    static::$results[$cid] = $cached;
    
    if ($this->cacheBackend) {
      $this->cacheBackend->store($cid, $cached);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function delete(string $filepath) {
    $realpath = realpath($filepath);
    $cid = "$this->collection:$realpath";
    unset(static::$results[$cid]);
    
    if ($this->cacheBackend) {
      $this->cacheBackend->delete($cid);
    }
  }
}
