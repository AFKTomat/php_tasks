<?php
/**
 * Пример 5: Работа с формами в PHP
 *
 * Демонстрация получения данных из $_GET и $_POST,
 * санитизация и базовая валидация
 */

echo "<h1>Работа с формами в PHP</h1>";

// ============================================
// Суперглобальные массивы $_GET и $_POST
// ============================================
echo "<h2>Суперглобальные массивы:</h2>";
echo "<p><strong>\$_GET</strong> — данные из URL (метод GET). Пример: <code>?name=Иван&age=25</code></p>";
echo "<p><strong>\$_POST</strong> — данные из тела запроса (метод POST). Не видны в адресной строке.</p>";

// Безопасное получение значения (оператор ?? — «если не задано, то...»)
$имя_из_get = $_GET['name'] ?? 'не передано';
$возраст_из_get = $_GET['age'] ?? 'не передано';

echo "<h3>Текущие значения из \$_GET:</h3>";
echo "name: " . htmlspecialchars($имя_из_get) . "<br>";
echo "age: " . htmlspecialchars((string)$возраст_из_get) . "<br>";

// ============================================
// Зачем htmlspecialchars?
// ============================================
echo "<h2>Экранирование вывода (htmlspecialchars):</h2>";
echo "<p>Всегда выводите пользовательские данные через <code>htmlspecialchars()</code>, " .
     "чтобы избежать XSS (внедрение скриптов в страницу).</p>";
echo "<p>Пример: если пользователь введёт <code>&lt;script&gt;alert(1)&lt;/script&gt;</code>, " .
     "без экранирования выполнится скрипт; с htmlspecialchars — будет показан как текст.</p>";

// ============================================
// Простая форма с методом GET
// ============================================
echo "<h2>Форма с методом GET:</h2>";
?>
<form method="GET" style="background:#fff; padding:15px; border-radius:5px; margin:10px 0;">
    <label>Имя: <input type="text" name="name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>"></label><br><br>
    <label>Возраст: <input type="number" name="age" value="<?= htmlspecialchars($_GET['age'] ?? '') ?>"></label><br><br>
    <button type="submit">Отправить (GET)</button>
</form>
<?php
if (!empty($_GET['name']) || isset($_GET['age'])) {
    echo "<p><strong>Получено через GET:</strong> имя = " . htmlspecialchars($_GET['name'] ?? '') .
         ", возраст = " . htmlspecialchars($_GET['age'] ?? '') . "</p>";
}

// ============================================
// Форма с методом POST (обработка на этой же странице)
// ============================================
echo "<h2>Форма с методом POST:</h2>";
$сообщение = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $логин = trim($_POST['login'] ?? '');
    $пароль = $_POST['password'] ?? '';
    if ($логин !== '' && $пароль !== '') {
        $сообщение = "Получено: логин = " . htmlspecialchars($логин) . ", пароль (длина) = " . strlen($пароль) . " символов.";
    } else {
        $сообщение = "Заполните оба поля.";
    }
}
?>
<form method="POST" style="background:#fff; padding:15px; border-radius:5px; margin:10px 0;">
    <label>Логин: <input type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"></label><br><br>
    <label>Пароль: <input type="password" name="password"></label><br><br>
    <button type="submit">Отправить (POST)</button>
</form>
<?php
if ($сообщение !== '') {
    echo "<p><strong>Результат:</strong> $сообщение</p>";
}

// ============================================
// Санитизация и валидация
// ============================================
echo "<h2>Санитизация и валидация:</h2>";
echo "<ul>";
echo "<li><strong>trim()</strong> — убрать пробелы по краям</li>";
echo "<li><strong>filter_var(\$email, FILTER_VALIDATE_EMAIL)</strong> — проверка email</li>";
echo "<li><strong>filter_var(\$url, FILTER_VALIDATE_URL)</strong> — проверка URL</li>";
echo "<li><strong>ctype_digit(\$str)</strong> — строка из цифр</li>";
echo "<li><strong>htmlspecialchars(\$str, ENT_QUOTES, 'UTF-8')</strong> — экранирование для HTML</li>";
echo "</ul>";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Формы в PHP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1, h2, h3 { color: #333; }
        code { background: #e8e8e8; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <p><a href="04_arrays.php">← Назад к массивам</a> | <a href="06_sessions.php">Далее: Сессии →</a></p>
</body>
</html>
