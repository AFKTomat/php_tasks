<?php
declare(strict_types=1);

/**
 * Задание 7: генерация PNG-капчи (GD + сессия).
 * Фон noise.jpg, 5–6 символов, TTF 18–30 pt, шаг 40 pt, разные цвета (≥1 красный),
 * поверх символов — точки и линии разной толщины/цвета/длины.
 */
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/captcha_lib.php';

if (!extension_loaded('gd')) {
    ob_end_clean();
    header('Content-Type: image/png');
    header('Cache-Control: no-store');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    exit;
}

$code = $_SESSION['captcha_code'] ?? '';
if ($code === '') {
    $code = captcha_generate_string(captcha_random_length());
    $_SESSION['captcha_code'] = $code;
}

$len = strlen($code);
$spacing = 40;
$marginX = 22;
$imgW = (int) ($marginX * 2 + max(1, $len - 1) * $spacing + 36);
$imgH = 100;

$noisePath = __DIR__ . '/../noise.jpg';
$img = imagecreatetruecolor($imgW, $imgH);
if ($img === false) {
    ob_end_clean();
    header('Content-Type: image/png');
    header('Cache-Control: no-store');
    exit;
}
imagealphablending($img, true);

if (is_readable($noisePath)) {
    $src = @imagecreatefromjpeg($noisePath);
    if ($src !== false) {
        imagecopyresampled($img, $src, 0, 0, 0, 0, $imgW, $imgH, imagesx($src), imagesy($src));
        imagedestroy($src);
    } else {
        $bg = imagecolorallocate($img, 220, 220, 228);
        imagefill($img, 0, 0, $bg);
    }
} else {
    $bg = imagecolorallocate($img, 220, 220, 228);
    imagefill($img, 0, 0, $bg);
}

$font = captcha_resolve_font();
$redIndex = random_int(0, $len - 1);

if ($font !== null && function_exists('imagettftext')) {
    for ($i = 0; $i < $len; $i++) {
        $char = $code[$i];
        $size = random_int(18, 30);
        $angle = (float) random_int(-14, 14);
        if ($i === $redIndex) {
            $r = random_int(180, 255);
            $g = random_int(0, 70);
            $b = random_int(0, 70);
        } else {
            $r = random_int(20, 120);
            $g = random_int(40, 140);
            $b = random_int(20, 120);
        }
        $col = imagecolorallocate($img, $r, $g, $b);
        $x = $marginX + $i * $spacing + random_int(-4, 4);
        $bbox = imagettfbbox($size, $angle, $font, $char);
        if ($bbox === false) {
            continue;
        }
        $minY = min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);
        $maxY = max($bbox[1], $bbox[3], $bbox[5], $bbox[7]);
        $charH = $maxY - $minY;
        $y = (int) (($imgH - $charH) / 2 - $minY + random_int(-5, 5));
        imagettftext($img, $size, $angle, $x, $y, $col, $font, $char);
    }
} else {
    for ($i = 0; $i < $len; $i++) {
        $char = $code[$i];
        $size = random_int(3, 5);
        if ($i === $redIndex) {
            $col = imagecolorallocate($img, 220, 30, 30);
        } else {
            $col = imagecolorallocate($img, random_int(20, 90), random_int(40, 100), random_int(30, 90));
        }
        $x = 12 + $i * 32;
        $y = 18 + random_int(0, 22);
        imagestring($img, $size, $x, $y, $char, $col);
    }
}

$lineCount = random_int(10, 18);
for ($n = 0; $n < $lineCount; $n++) {
    imagesetthickness($img, random_int(1, 3));
    $lc = imagecolorallocatealpha(
        $img,
        random_int(40, 200),
        random_int(40, 200),
        random_int(40, 200),
        random_int(60, 110)
    );
    imageline(
        $img,
        random_int(0, $imgW),
        random_int(0, $imgH),
        random_int(0, $imgW),
        random_int(0, $imgH),
        $lc
    );
}
imagesetthickness($img, 1);

$dotCount = random_int(80, 160);
for ($n = 0; $n < $dotCount; $n++) {
    $dc = imagecolorallocatealpha(
        $img,
        random_int(0, 255),
        random_int(0, 255),
        random_int(0, 255),
        random_int(40, 100)
    );
    imagesetpixel($img, random_int(0, $imgW - 1), random_int(0, $imgH - 1), $dc);
}

ob_end_clean();
header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate');
imagepng($img);
imagedestroy($img);
