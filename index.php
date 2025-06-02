<?php

use Snr\Psr16cache\FileCacheFactory;

$dir = dirname(__FILE__);
require_once "$dir/vendor/autoload.php";

// Example
$file_cache_factory = new FileCacheFactory('any_factory_id');
$file_cache = $file_cache_factory->get('plugins');
$filename = "$dir\index.php";
$file_cache->set($filename, file_get_contents($filename));
$contents = $file_cache->get($filename);

return;