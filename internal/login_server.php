<?php
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = require "db_connection.php";

// Grab POST data
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

// Check for missing fields
if (!$email || !$password) {
    $_SESSION['error'] = "Missing email or password";
    header("Location: /web/templates/pages/login_page.php");
    exit();
}

try {
    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: /web/templates/pages/login_page.php");
        exit();
    }

    // Login success: set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];

    header("Location: /index.php");
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = "Server error: " . $e->getMessage();
    header("Location: /web/templates/pages/login_page.php");
    exit();
}
