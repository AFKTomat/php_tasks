<?php
/**
 * Генератор демо-изображений для примера 08 (GD).
 * Вызов: 08_gd_gen.php?demo=basic|shapes|text|colors
 * До вывода картинки ничего не выводим (буфер + ob_end_clean перед header).
 */
ob_start();

if (!extension_loaded('gd')) {
    ob_end_clean();
    header('Content-Type: image/png');
    header('Cache-Control: no-store');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    exit;
}

$demo = $_GET['demo'] ?? 'basic';
$w = 280;
$h = 140;

$img = imagecreatetruecolor($w, $h);
if ($img === false) {
    ob_end_clean();
    header('Content-Type: image/png');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    exit;
}

$white = imagecolorallocate($img, 255, 255, 255);
$gray  = imagecolorallocate($img, 240, 240, 245);
$black = imagecolorallocate($img, 0, 0, 0);
$red   = imagecolorallocate($img, 220, 60, 60);
$blue  = imagecolorallocate($img, 60, 100, 200);
$green = imagecolorallocate($img, 60, 160, 80);

imagefill($img, 0, 0, $gray);

switch ($demo) {
    case 'shapes':
        // Прямоугольник (залитый), эллипс (контур), линия
        imagefilledrectangle($img, 20, 20, 120, 90, $red);
        imageellipse($img, 200, 70, 100, 80, $blue);
        imageline($img, 10, 130, 270, 10, $green);
        break;
    case 'text':
        // Текст встроенным шрифтом (1–5)
        imagestring($img, 5, 30, 25, 'PHP GD', $black);
        imagestring($img, 4, 30, 60, 'imagestring(size, x, y, text, color)', $blue);
        imagestring($img, 3, 30, 95, 'Size: 1 (small) to 5 (large)', $black);
        break;
    case 'colors':
        // Палитра: несколько прямоугольников разного цвета
        $colors = [
            [255, 99, 71],   [50, 205, 50],   [65, 105, 225],
            [255, 215, 0],   [148, 0, 211],   [0, 206, 209],
        ];
        $x = 15;
        foreach ($colors as $rgb) {
            $c = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
            imagefilledrectangle($img, $x, 50, $x + 38, 90, $c);
            $x += 42;
        }
        imagestring($img, 3, 20, 105, 'imagecolorallocate($img, R, G, B)', $black);
        break;
    case 'basic':
    default:
        // Базовый пример: прямоугольник и круг
        imagefilledrectangle($img, 30, 30, 130, 100, $red);
        imagefilledellipse($img, 200, 65, 80, 60, $blue);
        imagestring($img, 4, 95, 115, 'GD', $black);
        break;
}

ob_end_clean();
header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache');
imagepng($img);
imagedestroy($img);
