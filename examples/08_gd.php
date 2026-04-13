<?php
/**
 * Пример 8: Библиотека GD — работа с изображениями
 *
 * GD позволяет создавать и изменять картинки: фон, фигуры, текст, загрузка JPEG/PNG.
 * Картинки для демо генерирует скрипт 08_gd_gen.php (отдельный запрос, чтобы до PNG ничего не выводилось).
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD — работа с изображениями</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1, h2, h3 { color: #333; }
        .demo { background: #fff; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .demo img { display: block; border: 1px solid #ccc; margin: 10px 0; }
        code { background: #eee; padding: 2px 6px; border-radius: 3px; }
        ul { margin: 8px 0; }
    </style>
</head>
<body>
    <h1>Пример 8: Библиотека GD</h1>
    <p>GD — расширение PHP для создания и изменения растровых изображений (PNG, JPEG, GIF и др.).</p>

    <h2>1. Проверка подключения</h2>
    <p>Расширение загружено: <strong><?= extension_loaded('gd') ? 'Да' : 'Нет' ?></strong>. Если «Нет», включите <code>extension=gd</code> в <code>php.ini</code>.</p>

    <h2>2. Создание холста и цвета</h2>
    <ul>
        <li><code>imagecreatetruecolor($width, $height)</code> — создать изображение в истинном цвете.</li>
        <li><code>imagecolorallocate($img, $r, $g, $b)</code> — определить цвет (0–255 на канал).</li>
        <li><code>imagefill($img, $x, $y, $color)</code> — залить область (например, весь фон).</li>
    </ul>

    <h2>3. Демо: базовый рисунок (прямоугольник, эллипс, подпись)</h2>
    <div class="demo">
        <img src="08_gd_gen.php?demo=basic" alt="basic" width="280" height="140">
        <p>Код: <code>imagefilledrectangle</code>, <code>imagefilledellipse</code>, <code>imagestring</code>.</p>
    </div>

    <h2>4. Демо: фигуры (прямоугольник, эллипс-контур, линия)</h2>
    <div class="demo">
        <img src="08_gd_gen.php?demo=shapes" alt="shapes" width="280" height="140">
        <p>Контур: <code>imageellipse</code>, <code>imageline</code>. Заливка: <code>imagefilledrectangle</code>.</p>
    </div>

    <h2>5. Демо: текст (imagestring)</h2>
    <div class="demo">
        <img src="08_gd_gen.php?demo=text" alt="text" width="280" height="140">
        <p><code>imagestring($img, $size, $x, $y, $text, $color)</code> — размер шрифта от 1 (мелкий) до 5 (крупный). Для своего шрифта (TTF) используют <code>imagettftext</code>.</p>
    </div>

    <h2>6. Демо: палитра цветов</h2>
    <div class="demo">
        <img src="08_gd_gen.php?demo=colors" alt="colors" width="280" height="140">
        <p>Цвета задаются через <code>imagecolorallocate($img, R, G, B)</code>.</p>
    </div>

    <h2>7. Вывод изображения в браузер</h2>
    <ul>
        <li>До вывода картинки не должно быть никакого вывода (пробелы, HTML, echo). Иначе заголовки не отправятся и браузер покажет «сломанную» иконку.</li>
        <li>В начале скрипта: <code>ob_start();</code>, перед <code>header('Content-Type: image/png');</code>: <code>ob_end_clean();</code>.</li>
        <li><code>header('Content-Type: image/png');</code> и затем <code>imagepng($img);</code>. Сохранение в файл: <code>imagepng($img, 'path/to/file.png');</code>.</li>
    </ul>

    <p><a href="07_captcha.php">← Назад к капче</a> | <a href="../tasks/task_06.php">Задание 6 (GD)</a> | <a href="../tasks/task_01.php">Все задания</a> | <a href="08_gd_gen.php?demo=basic">Только картинка (basic)</a></p>
</body>
</html>
