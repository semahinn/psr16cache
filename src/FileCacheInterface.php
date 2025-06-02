<?php

namespace Snr\Psr16cache;

/**
 * Описывает методы для:
 * 1. Получения содержимого файла/файлов из кэша
 * 2. Передачи содержимого файла в кэш
 * 3. Очистки кэша файлов
 */
interface FileCacheInterface {

  /**
   * Возвращает содержимое файла за кэша
   *
   * @param string $filepath
   *  Путь к файлу
   *
   * @return mixed|null
   *  Содержимое этого файла в кэше или null
   */
  public function get(string $filepath);

  /**
   * Возвращает содержимое нескольких файлов
   *
   * @param string[] $filepaths
   *  Массив путей к файлам
   *
   * @return array
   *  Массив, ключами которого являются пути файлов,
   *  а значениями - содержимое этих файлов
   */
  public function getMultiple(array $filepaths);

  /**
   * Добавляет содержимое файла в кэш
   *
   * @param string $filepath
   *  Путь к файлу
   *
   * @param mixed $data
   *  Содержимое файла
   */
  public function set(string $filepath, $data);

  /**
   * Удаляет содержимое файла из кэша
   *
   * @param string $filepath
   *  Путь к файлу
   */
  public function delete(string $filepath);

  /**
   * Очищает кэш
   *
   * @return void
   */
  public function clear();
}
