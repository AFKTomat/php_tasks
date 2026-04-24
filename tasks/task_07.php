<?php
declare(strict_types=1);

/**
 * Задание 7: форма с капчей-картинкой, проверка через $_SESSION.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/captcha_lib.php';

$message = '';
$success = false;

if (isset($_GET['new']) && $_GET['new'] === '1') {
    $_SESSION['captcha_code'] = captcha_generate_string(captcha_random_length());
    header('Location: task_07.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_input'])) {
    $input = captcha_normalize_input((string) ($_POST['captcha_input'] ?? ''));
    $expected = captcha_normalize_input((string) ($_SESSION['captcha_code'] ?? ''));
    unset($_SESSION['captcha_code']);
    if ($input !== '' && $expected !== '' && $input === $expected) {
        $success = true;
        $message = 'Правильно';
    } else {
        $message = 'Не корректно';
    }
    $_SESSION['captcha_code'] = captcha_generate_string(captcha_random_length());
}

if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = captcha_generate_string(captcha_random_length());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 7: CAPTCHA</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 480px; margin: 48px auto; padding: 20px; background: #f0f0f0; }
        .box { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        h1 { font-size: 1.35rem; margin-top: 0; }
        .msg { margin-top: 14px; padding: 10px 12px; border-radius: 4px; }
        .msg.ok { background: #e8f5e9; color: #1b5e20; }
        .msg.err { background: #ffebee; color: #b71c1c; }
        img.captcha { display: block; margin: 14px 0; border: 1px solid #ccc; max-width: 100%; height: auto; }
        label { display: block; margin-bottom: 6px; }
        input[type="text"] { padding: 8px 10px; width: 100%; max-width: 280px; box-sizing: border-box; }
        .actions { margin-top: 12px; }
        button { padding: 8px 22px; background: #1976d2; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button:hover { background: #1565c0; }
        a.refresh { font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Регистрация</h1>
        <p>Картинка генерируется скриптом <code>task_07_gen.php</code>; код хранится в сессии.</p>

        <?php if ($message !== ''): ?>
            <p class="msg <?= $success ? 'ok' : 'err' ?>"><?= htmlspecialchars($message, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <img class="captcha" src="task_07_gen.php" alt="CAPTCHA">
            <p><a class="refresh" href="task_07.php?new=1">Обновить капчу</a></p>
            <label for="captcha_input">Введите строку</label>
            <input id="captcha_input" type="text" name="captcha_input" autocomplete="off" maxlength="16" required>
            <div class="actions">
                <button type="submit">OK</button>
            </div>
        </form>
    </div>
    <p style="margin-top: 22px;"><a href="task_06.php">← Задание 6</a> | <a href="task_05.php">Задание 5</a></p>
</body>
</html>
