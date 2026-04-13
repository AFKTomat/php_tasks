<?php
/**
 * Пример 6: Сессии в PHP
 *
 * Сессия — способ хранить данные на сервере между запросами.
 * Идентификатор сессии передаётся в cookie (или в URL).
 * Нужно вызывать session_start() до любого вывода в браузер.
 */

// Старт сессии (обязательно в начале, до echo/HTML)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>Сессии в PHP</h1>";

// ============================================
// Запись и чтение данных сессии
// ============================================
echo "<h2>Запись и чтение \$_SESSION:</h2>";

// Инициализируем счётчик при первом заходе
if (!isset($_SESSION['счётчик_посещений'])) {
    $_SESSION['счётчик_посещений'] = 0;
}
$_SESSION['счётчик_посещений']++;

echo "<p>Количество обновлений этой страницы в текущей сессии: <strong>" . (int)$_SESSION['счётчик_посещений'] . "</strong></p>";

// Сохраняем время первого визита
if (!isset($_SESSION['первый_визит'])) {
    $_SESSION['первый_визит'] = date('Y-m-d H:i:s');
}
echo "<p>Первый визит в этой сессии: " . htmlspecialchars($_SESSION['первый_визит']) . "</p>";

// ============================================
// Идентификатор сессии
// ============================================
echo "<h2>Идентификатор сессии:</h2>";
echo "<p>session_id(): <code>" . htmlspecialchars(session_id()) . "</code></p>";
echo "<p>По этому ID сервер находит ваши данные в хранилище сессий.</p>";

// ============================================
// Форма: добавить имя в сессию
// ============================================
echo "<h2>Сохранение данных из формы в сессию:</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['имя_пользователя'])) {
    $имя = trim($_POST['имя_пользователя']);
    if ($имя !== '') {
        $_SESSION['имя_пользователя'] = $имя;
    }
}

if (isset($_SESSION['имя_пользователя'])) {
    echo "<p>В сессии сохранено имя: <strong>" . htmlspecialchars($_SESSION['имя_пользователя']) . "</strong></p>";
}
?>
<form method="POST" style="background:#fff; padding:15px; border-radius:5px; margin:10px 0;">
    <label>Ваше имя (сохраним в сессии): <input type="text" name="имя_пользователя" value="<?= htmlspecialchars($_SESSION['имя_пользователя'] ?? '') ?>"></label><br><br>
    <button type="submit">Сохранить в сессии</button>
</form>

<?php
// Кнопка «Выйти» — уничтожение сессии
if (isset($_GET['logout'])) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    header('Location: 06_sessions.php');
    exit;
}
?>
<p><a href="06_sessions.php?logout=1" style="color:#c00;">Завершить сессию (выйти)</a></p>

<!-- ============================================ -->
<!-- Краткая справка -->
<!-- ============================================ -->
<h2>Основные функции:</h2>
<ul>
    <li><strong>session_start()</strong> — запустить сессию (вызывать один раз в начале скрипта)</li>
    <li><strong>$_SESSION['ключ'] = значение</strong> — записать данные</li>
    <li><strong>session_destroy()</strong> — уничтожить сессию (обычно после очистки $_SESSION)</li>
    <li><strong>session_id()</strong> — получить ID текущей сессии</li>
</ul>
<p>Сессии часто используют для: авторизации (логин/пароль), корзины, данных многошаговых форм.</p>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сессии в PHP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1, h2 { color: #333; }
        code { background: #e8e8e8; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <p><a href="05_forms.php">← Назад к формам</a> | <a href="07_captcha.php">Далее: Капча →</a></p>
</body>
</html>
