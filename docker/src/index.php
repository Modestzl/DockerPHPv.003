<?php
// ./src/index.php

// Устанавливаем кодировку
header('Content-Type: text/html; charset=utf-8');

// Получаем переменные окружения
$mysql_host = getenv('MYSQL_HOST') ?: 'mysql';
$mysql_port = getenv('MYSQL_PORT') ?: '3306';
$mysql_database = getenv('MYSQL_DATABASE') ?: 'app_db';
$mysql_user = getenv('MYSQL_USER') ?: 'app_user';
$mysql_password = getenv('MYSQL_PASSWORD') ?: 'app_password';

// Подключение к MySQL
try {
    $dsn = "mysql:host={$mysql_host};port={$mysql_port};dbname={$mysql_database};charset=utf8mb4";
    $pdo = new PDO($dsn, $mysql_user, $mysql_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    echo "<h1>✅ Подключение к MySQL успешно!</h1>";

    // Пример запроса
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo "<p>Версия MySQL: " . htmlspecialchars($version['version']) . "</p>";

    // Создаем тестовую таблицу если ее нет
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS test_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            message VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // Вставляем тестовые данные
    $stmt = $pdo->prepare("INSERT INTO test_table (message) VALUES (?)");
    $stmt->execute(["Привет из Docker!"]);

    // Читаем данные
    $stmt = $pdo->query("SELECT * FROM test_table ORDER BY created_at DESC");
    $results = $stmt->fetchAll();

    echo "<h2>Данные из базы:</h2>";
    echo "<ul>";
    foreach ($results as $row) {
        echo "<li>" . htmlspecialchars($row['message']) .
            " (ID: {$row['id']}, создано: {$row['created_at']})</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "<h1>❌ Ошибка подключения к MySQL</h1>";
    echo "<p>Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Проверьте:</p>";
    echo "<ul>";
    echo "<li>Запущен ли контейнер MySQL</li>";
    echo "<li>Правильность параметров подключения</li>";
    echo "<li>Файл .env с переменными окружения</li>";
    echo "</ul>";
}

// Информация о PHP
echo "<hr>";
echo "<h2>Информация о PHP:</h2>";
echo "<p>Версия PHP: " . phpversion() . "</p>";
echo "<p>Расширения PHP:</p>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $ext) {
    echo "<li>" . htmlspecialchars($ext) . "</li>";
}
echo "</ul>";

// Информация о сервере
echo "<hr>";
echo "<h2>Информация о сервере:</h2>";
echo "<p>Сервер: " . htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "</p>";
echo "<p>IP сервера: " . htmlspecialchars($_SERVER['SERVER_ADDR'] ?? 'N/A') . "</p>";