<?php

namespace Snr\Psr16cache;

/**
 * Каждая реализация этого интерфейса описывает определённый способ:
 * 1. Поиска данных для передачи их в кэш
 * 2. Хранения и удаления этих данных
 *
 * Т.о., экземпляр (@see FileCache::$cacheBackend) можно
 * настраивать на использование одного из таких способов
 */
interface FileCacheBackendInterface
{
  public function fetch(array $cids);

  public function store($cid, $data);

  public function delete($cid);
}