<?php
declare(strict_types=1);

/**
 * Задание 7: форма «Регистрация» + капча, проверка через $_SESSION.
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

$gdMissing = !extension_loaded('gd');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 7: CAPTCHA</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            max-width: 520px;
            margin: 24px auto;
            padding: 16px;
            background: #fff;
            color: #000;
        }
        h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 0 20px 0;
        }
        img.captcha {
            display: block;
            margin: 0 0 8px 0;
            border: 1px solid #999;
            max-width: 100%;
            height: auto;
        }
        .refresh {
            font-size: 0.85rem;
            margin: 0 0 16px 0;
        }
        .refresh a { color: #00e; }
        label {
            display: block;
            margin-bottom: 4px;
        }
        input[type="text"] {
            padding: 3px 6px;
            width: 100%;
            max-width: 320px;
            box-sizing: border-box;
            border: 1px solid #000;
            font: inherit;
        }
        .actions { margin-top: 12px; }
        button[type="submit"] {
            padding: 4px 14px;
            font: inherit;
            background: #e0e0e0;
            color: #000;
            border: 1px solid #7a7a7a;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background: #d0d0d0;
        }
        .msg {
            margin: 14px 0 0 0;
            padding: 0;
        }
        .msg.ok { color: #006400; }
        .msg.err { color: #8b0000; }
        .nav { margin-top: 28px; font-size: 0.95rem; }
        .nav a { color: #00e; }
        .gd-missing-alert {
            background: #fff8e1;
            border: 1px solid #f9a825;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: 0.9rem;
            line-height: 1.45;
        }
        .gd-missing-alert code { background: #f5f5f5; padding: 1px 4px; word-break: break-all; }
    </style>
</head>
<body>
    <h1>Регистрация</h1>

    <?php if ($gdMissing): ?>
        <?php
        $iniPath = php_ini_loaded_file();
        $iniShown = $iniPath !== false ? $iniPath : '(ini не загружен — положите php.ini рядом с php.exe, см. php.ini-development)';
        ?>
        <div class="gd-missing-alert">
            <p><strong>Капча не рисуется:</strong> у <strong>этого</strong> PHP не загружено расширение <code>gd</code>
            (без него <code>task_07_gen.php</code> отдаёт заглушку 1×1 px).</p>
            <p><strong>Кто обрабатывает страницу сейчас:</strong><br>
            <code><?= htmlspecialchars(PHP_BINARY, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></code><br>
            <code>php.ini</code>: <code><?= htmlspecialchars($iniShown, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></code></p>
            <p>Откройте в том же браузере: <a href="php_env.php"><code>php_env.php</code></a> — там те же данные текстом.
            Сравните с терминалом: <code>where php</code> и <code>php -r "echo PHP_BINARY;"</code>.
            Если пути <strong>разные</strong>, страницу обслуживает <strong>другой</strong> PHP — включите <code>extension=gd</code> в <strong>его</strong> <code>php.ini</code>.</p>
            <p><strong>Остановите</strong> старый процесс <code>php -S</code> (Ctrl+C) и запустите снова.
            Удобно из корня проекта: <code>.\start-server.ps1</code> (проверит GD перед стартом).</p>
            <p>В <code>php.ini</code> рядом с нужным <code>php.exe</code>: раскомментируйте <code>extension_dir = "ext"</code> и <code>extension=gd</code>, сохраните файл.</p>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <img class="captcha" src="task_07_gen.php" alt="Капча">
        <p class="refresh"><a href="task_07.php?new=1">Обновить капчу</a></p>
        <label for="captcha_input">Введите строку</label>
        <input id="captcha_input" type="text" name="captcha_input" autocomplete="off" maxlength="16" required>
        <div class="actions">
            <button type="submit">OK</button>
        </div>
    </form>

    <?php if ($message !== ''): ?>
        <p class="msg <?= $success ? 'ok' : 'err' ?>"><?= htmlspecialchars($message, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></p>
    <?php endif; ?>

    <p class="nav"><a href="task_06.php">← Задание 6</a> | <a href="task_05.php">Задание 5</a></p>
</body>
</html>
