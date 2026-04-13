<?php
/**
 * ЗАДАНИЕ 7 (часть 1): Генератор изображения капчи
 *
 * Этот скрипт должен выводить PNG-картинку капчи (200×70):
 * — серый фон (imagecreatetruecolor + imagefill);
 * — 5–8 случайных линий (imageline) поверх фона;
 * — код капчи (5 цифр) из сессии — нарисовать через imagestring с разным размером (3, 4 или 5) и смещением по Y.
 *
 * В начале: ob_start(), session_start(). Код брать из $_SESSION['captcha_code'] (если пусто — сгенерировать и сохранить).
 * Перед выводом: ob_end_clean(), header('Content-Type: image/png'), imagepng($img).
 * Если GD не загружен — вывести заглушку (1×1 PNG) и exit.
 */
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!extension_loaded('gd')) {
    ob_end_clean();
    header('Content-Type: image/png');
    header('Cache-Control: no-store');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    exit;
}

$code = $_SESSION['captcha_code'] ?? '';
if ($code === '') {
    $code = (string) random_int(10000, 99999);
    $_SESSION['captcha_code'] = $code;
}

$w = 200;
$h = 70;
$img = imagecreatetruecolor($w, $h);
$bg = imagecolorallocate($img, 232, 232, 238);
imagefill($img, 0, 0, $bg);

$lineColor1 = imagecolorallocate($img, 120, 120, 130);
$lineColor2 = imagecolorallocate($img, 100, 100, 110);
for ($i = 0; $i < 6; $i++) {
    imageline($img, random_int(0, $w), random_int(0, $h), random_int(0, $w), random_int(0, $h), $i % 2 ? $lineColor1 : $lineColor2);
}

$textColor = imagecolorallocate($img, 40, 40, 50);
$len = strlen($code);
$sizes = [3, 4, 5];
for ($i = 0; $i < $len; $i++) {
    $char = $code[$i];
    $size = $sizes[array_rand($sizes)];
    $x = 15 + $i * 36;
    $y = 18 + random_int(0, 18);
    imagestring($img, $size, $x, $y, $char, $textColor);
}

ob_end_clean();
header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache');
imagepng($img);
imagedestroy($img);
