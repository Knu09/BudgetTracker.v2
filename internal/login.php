<?php

session_start();

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = require "db_connection.php";

// $data = json_decode(file_get_contents("php://input"), true);

$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;


if (!$email || !$password) {
    echo json_encode(["success" => false, "message" => "Missing email or password"]);
    exit();
}

try {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    //if no user exits
    if (!$user) {
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
        $_SESSION['error'] = "Invalid email or password";
        header("Location: ../web/templates/pages/login_page.php");
        exit();
    }

    //pasword not same
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Invalid email or password";
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
        header("Location: ../web/templates/pages/login_page.php");
        exit();
    }

    // unset($user['password']);
    echo json_encode(["success" => true, "message" => "Login successful", "user" => $user]);
    header("Location: ../index.php");
    exit();
} catch (Exception $e) {
    echo json_encode(["error" => "Server error", "message" => $e->getMessage()]);
    exit();
}
