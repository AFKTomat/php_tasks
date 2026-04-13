<?php
/**
 * ЗАДАНИЕ 6: Рисование изображения с помощью GD
 *
 * Дополните код так, чтобы скрипт выводил одну PNG-картинку 300×150:
 * — фон светло-серый (235, 235, 240);
 * — красный залитый прямоугольник (20, 20) — (120, 100);
 * — синий залитый эллипс: центр (220, 75), ширина 100, высота 60;
 * — чёрный текст «PHP» через imagestring($img, 5, $x, $y, 'PHP', $black).
 *
 * До вывода картинки ничего не выводить: ob_start() в начале, ob_end_clean() перед header.
 */
ob_start();

if (!extension_loaded('gd')) {
    ob_end_clean();
    header('Content-Type: image/png');
    header('Cache-Control: no-store');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    exit;
}

$img = imagecreatetruecolor(300, 150);
$bg = imagecolorallocate($img, 235, 235, 240);
imagefill($img, 0, 0, $bg);

$red = imagecolorallocate($img, 220, 40, 40);
imagefilledrectangle($img, 20, 20, 120, 100, $red);

$blue = imagecolorallocate($img, 40, 90, 220);
imagefilledellipse($img, 220, 75, 100, 60, $blue);

$black = imagecolorallocate($img, 0, 0, 0);
imagestring($img, 5, 130, 65, 'PHP', $black);

ob_end_clean();
header('Content-Type: image/png');
header('Cache-Control: no-store');
imagepng($img);
imagedestroy($img);
