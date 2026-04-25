<?php
declare(strict_types=1);

/**
 * Диагностика: какой PHP реально обрабатывает запрос из браузера (GD, пути).
 * Откройте: http://localhost:ПОРТ/tasks/php_env.php
 */
header('Content-Type: text/plain; charset=utf-8');

echo "PHP_VERSION=" . PHP_VERSION . "\n";
echo "PHP_BINARY=" . PHP_BINARY . "\n";
echo "PHP_SAPI=" . php_sapi_name() . "\n";

$ini = php_ini_loaded_file();
echo 'php_ini_loaded_file=' . ($ini !== false ? $ini : '(none — создайте php.ini рядом с php.exe из php.ini-development)') . "\n";

echo 'extension_loaded(gd)=' . (extension_loaded('gd') ? 'yes' : 'no') . "\n";

if (extension_loaded('gd')) {
    $info = gd_info();
    echo 'GD JPEG=' . (($info['JPEG Support'] ?? false) ? 'yes' : 'no') . "\n";
    echo 'GD FreeType=' . (($info['FreeType Support'] ?? false) ? 'yes' : 'no') . "\n";
}
