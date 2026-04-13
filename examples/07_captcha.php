<?php
/**
 * Пример 7: Простая текстовая капча
 *
 * Капча — проверка «ты человек». Мы генерируем случайный код,
 * сохраняем его в сессии и просим пользователя ввести его.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$результат_проверки = '';
$код_капчи = '';

// Сначала обрабатываем POST: берём код из сессии до любой генерации нового
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_input'])) {
    $ввод = trim($_POST['captcha_input']);
    $ожидается = $_SESSION['captcha_code'] ?? '';
    unset($_SESSION['captcha_code']);

    if ($ввод !== '' && $ожидается !== '' && $ввод === $ожидается) {
        $результат_проверки = '<span style="color:green;">Верно! Капча пройдена.</span>';
    } else {
        $результат_проверки = '<span style="color:red;">Ошибка. Попробуйте ещё раз.</span>';
    }
    $_SESSION['captcha_code'] = str_pad((string)random_int(0, 99999), 5, '0', STR_PAD_LEFT);
    $код_капчи = $_SESSION['captcha_code'];
}

// Генерация кода только когда не обрабатывали POST (или для первой загрузки / «Обновить капчу»)
if ($код_капчи === '' && (isset($_GET['new_captcha']) || !isset($_SESSION['captcha_code']))) {
    $цифры = '0123456789';
    $код = '';
    for ($i = 0; $i < 5; $i++) {
        $код .= $цифры[random_int(0, strlen($цифры) - 1)];
    }
    $_SESSION['captcha_code'] = $код;
}
if ($код_капчи === '') {
    $код_капчи = $_SESSION['captcha_code'];
}

echo "<h1>Простая капча в PHP</h1>";
echo "<h2>Как это устроено:</h2>";
echo "<ol>";
echo "<li>Сервер генерирует случайную строку (например, 5 цифр или буквенно-цифровой код).</li>";
echo "<li>Строка сохраняется в \$_SESSION.</li>";
echo "<li>Пользователь видит этот код на странице (или на картинке) и вводит его в поле.</li>";
echo "<li>При отправке формы сравниваем введённое значение с тем, что в сессии.</li>";
echo "</ol>";
?>

<h2>Демо: введите код с картинки</h2>
<p>Код капчи (в реальной форме его показывают на изображении): <strong><?= htmlspecialchars($код_капчи) ?></strong></p>
<p><a href="07_captcha.php?new_captcha=1">Обновить капчу</a></p>

<form method="POST" style="background:#fff; padding:15px; border-radius:5px; margin:10px 0;">
    <label>Введите код: <input type="text" name="captcha_input" autocomplete="off" maxlength="10"></label><br><br>
    <button type="submit">Проверить</button>
</form>

<?php if ($результат_проверки !== '') echo "<p><strong>Результат:</strong> $результат_проверки</p>"; ?>

<h2>Вариант: буквенно-цифровая капча</h2>
<?php
function сгенерировать_код_капчи(int $длина = 5): string {
    $символы = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // без 0,O,1,I для читаемости
    $код = '';
    $макс = strlen($символы) - 1;
    for ($i = 0; $i < $длина; $i++) {
        $код .= $символы[random_int(0, $макс)];
    }
    return $код;
}
$пример_кода = сгенерировать_код_капчи(5);
echo "<p>Пример сгенерированного кода: <code>" . htmlspecialchars($пример_кода) . "</code></p>";
echo "<p>Для вывода в виде изображения используют расширение <strong>GD</strong> (рисование текста на картинке).</p>";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Капча в PHP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1, h2 { color: #333; }
        code { background: #e8e8e8; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <p><a href="06_sessions.php">← Назад к сессиям</a> | <a href="08_gd.php">Далее: GD →</a> | <a href="../tasks/task_01.php">Перейти к заданиям</a></p>
</body>
</html>
