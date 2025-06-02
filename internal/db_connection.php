<?php

session_start();
$config = require '../config.php';


try {
    $conn = new mysqli(
        $config['host'],
        $config['user'],
        $config['pass'],
        $config['db']
    );
    echo "Success";
    return $conn;
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
