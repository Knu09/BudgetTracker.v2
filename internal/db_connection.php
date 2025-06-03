<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$config = require __DIR__ . '/../config.php';

try {
    $conn = new mysqli(
        $config['host'],
        $config['user'],
        $config['pass'],
        $config['db']
    );
    return $conn;
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
