<?php

namespace Snr\Psr16cache;

use Psr\SimpleCache\CacheInterface;

/**
 * Простая реализация файлового кэша,
 * совместимого с принятым в psr-16 интерфейсом кэша
 *
 * @see \Psr\SimpleCache\CacheInterface
 */
class Psr16FileCache extends FileCache implements CacheInterface {

  /**
   * {@inheritdoc}
   */
  public function get($filepath, $default = null) {
    return parent::get($filepath);
  }

  /**
   * {@inheritdoc}
   */
  public function getMultiple($filepaths, $default = null) {
    return parent::getMultiple($filepaths);
  }

  /**
   * {@inheritdoc}
   */
  public function has($filepath) {
    return (bool) $this->get($filepath);
  }

  /**
   * {@inheritdoc}
   */
  public function set($filepath, $data, $ttl = null) {
    return parent::set($filepath, $data);
  }

  /**
   * {@inheritdoc}
   */
  public function setMultiple($filepaths, $ttl = null) {
    foreach ($filepaths as $filepath) {
      $this->set($filepath, []);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function delete($filepath) {
    parent::delete($filepath);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple($keys) {
    foreach ($keys as $key) {
      $this->delete($key);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function clear() {
    static::$results = [];
  }
}