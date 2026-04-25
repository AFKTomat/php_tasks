<?php
declare(strict_types=1);

/**
 * Задание 7: PNG-капча — фон из noise.jpg (корень проекта), символы как на эталоне:
 * тёмные буквы/цифры с заметными промежутками, без «забитого» шума поверх текста.
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
$spacing = 48;
$marginX = 32;
$imgH = 78;
$imgW = (int) ($marginX * 2 + max(1, $len - 1) * $spacing + 44);

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
        $bg = imagecolorallocate($img, 240, 240, 235);
        imagefill($img, 0, 0, $bg);
    }
} else {
    $bg = imagecolorallocate($img, 240, 240, 235);
    imagefill($img, 0, 0, $bg);
}

$font = captcha_resolve_font();

$lineCount = random_int(3, 6);
for ($n = 0; $n < $lineCount; $n++) {
    imagesetthickness($img, 1);
    $lc = imagecolorallocatealpha(
        $img,
        random_int(90, 160),
        random_int(90, 160),
        random_int(90, 160),
        random_int(85, 118)
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

$dotCount = random_int(35, 70);
for ($n = 0; $n < $dotCount; $n++) {
    $dc = imagecolorallocatealpha(
        $img,
        random_int(60, 140),
        random_int(60, 140),
        random_int(60, 140),
        random_int(95, 120)
    );
    imagesetpixel($img, random_int(0, $imgW - 1), random_int(0, $imgH - 1), $dc);
}

if ($font !== null && function_exists('imagettftext')) {
    for ($i = 0; $i < $len; $i++) {
        $char = $code[$i];
        $size = random_int(22, 28);
        $angle = (float) random_int(-7, 7);
        $g = random_int(32, 52);
        $col = imagecolorallocate($img, $g, $g, $g);
        $x = $marginX + $i * $spacing + random_int(-2, 2);
        $bbox = imagettfbbox($size, $angle, $font, $char);
        if ($bbox === false) {
            continue;
        }
        $minY = min($bbox[1], $bbox[3], $bbox[5], $bbox[7]);
        $maxY = max($bbox[1], $bbox[3], $bbox[5], $bbox[7]);
        $charH = $maxY - $minY;
        $y = (int) (($imgH - $charH) / 2 - $minY + random_int(-3, 3));
        imagettftext($img, $size, $angle, $x, $y, $col, $font, $char);
    }
} else {
    for ($i = 0; $i < $len; $i++) {
        $char = $code[$i];
        $size = 5;
        $col = imagecolorallocate($img, 45, 45, 45);
        $x = 18 + $i * 38;
        $y = 22 + random_int(0, 8);
        imagestring($img, $size, $x, $y, $char, $col);
    }
}

ob_end_clean();
header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate');
imagepng($img);
imagedestroy($img);
