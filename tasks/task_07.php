<?php
/**
 * ЗАДАНИЕ 7 (часть 2): Форма с капчей-картинкой
 *
 * Форма с полем ввода и картинкой капчи (<img src="task_07_gen.php">).
 * Проверка введённого кода с $_SESSION['captcha_code']; после проверки — новый код в сессии.
 * Ссылка «Обновить капчу» (?new=1) задаёт новый код.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = '';
$success = false;

if (isset($_GET['new']) && $_GET['new'] === '1') {
    $_SESSION['captcha_code'] = (string) random_int(10000, 99999);
    header('Location: task_07.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_input'])) {
    $input = trim($_POST['captcha_input'] ?? '');
    $expected = $_SESSION['captcha_code'] ?? '';
    unset($_SESSION['captcha_code']);
    if ($input !== '' && $expected !== '' && $input === $expected) {
        $success = true;
        $message = 'Верно!';
    } else {
        $message = 'Неверный код.';
    }
    $_SESSION['captcha_code'] = (string) random_int(10000, 99999);
}

if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = (string) random_int(10000, 99999);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 7: Капча-картинка</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .box { background: #fff; padding: 20px; border-radius: 8px; }
        .msg { margin-top: 12px; padding: 10px; border-radius: 4px; }
        .msg.ok { background: #e8f5e9; color: #2e7d32; }
        .msg.err { background: #ffebee; color: #c62828; }
        img { display: block; margin: 10px 0; border: 1px solid #ccc; }
        input[type="text"] { padding: 8px; width: 120px; }
        button { margin-top: 8px; padding: 8px 16px; background: #4CAF50; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Задание 7: Капча-картинка</h1>
        <p>Введите код с картинки. Картинку генерирует <code>task_07_gen.php</code> (дополните в нём рисование линий и текста).</p>

        <?php if ($message !== ''): ?>
            <p class="msg <?= $success ? 'ok' : 'err' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <img src="task_07_gen.php" alt="Капча" width="200" height="70"><br>
            <a href="task_07.php?new=1">Обновить капчу</a><br><br>
            <label>Код: <input type="text" name="captcha_input" autocomplete="off" maxlength="10" required></label><br>
            <button type="submit">Проверить</button>
        </form>
    </div>
    <p style="margin-top: 20px;"><a href="task_06.php">← Задание 6</a> | <a href="task_05.php">Задание 5</a></p>
</body>
</html>
