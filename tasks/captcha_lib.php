<?php
declare(strict_types=1);

/**
 * Общая логика строки капчи для task_07 / task_07_gen.
 */
function captcha_random_length(): int
{
    return random_int(5, 6);
}

function captcha_generate_string(int $length): string
{
    if ($length < 5 || $length > 6) {
        $length = captcha_random_length();
    }
    $chars = 'abcdefghjkmnpqrstuvwxyz23456789';
    $max = strlen($chars) - 1;
    $out = '';
    for ($i = 0; $i < $length; $i++) {
        $out .= $chars[random_int(0, $max)];
    }
    return $out;
}

function captcha_normalize_input(string $input): string
{
    return strtolower(preg_replace('/\s+/u', '', $input));
}

/** Первый доступный TTF из списка (Windows / Linux). */
function captcha_resolve_font(): ?string
{
    $candidates = [
        'C:\\Windows\\Fonts\\arial.ttf',
        'C:\\Windows\\Fonts\\calibri.ttf',
        'C:\\Windows\\Fonts\\verdana.ttf',
        '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
        '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
    ];
    foreach ($candidates as $path) {
        if (is_readable($path)) {
            return $path;
        }
    }
    return null;
}
