<?php
$configPath = __DIR__ . '/config.php';

if (!file_exists($configPath)) {
    header('Location: /install.php');
    exit;
}

$config = require $configPath;
require_once __DIR__ . '/../src/Database.php';

$pdo = Database::connect($config);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
