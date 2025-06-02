<?php

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = require "db_connection.php";

// $data = json_decode(file_get_contents("php://input"), true);

$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

// check pass
if (!$email || !$password) {
    $message = "Missing required fields";
    echo json_encode(["success" => false, "message" => $message]);
    $_SESSION['error'] = $message;
    header("Location: ../web/templates/pages/register_page.php");
    exit();
}

// check email validity
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Invalid E-Mail";
    echo json_encode(["success" => false, "message" => $message]);
    $_SESSION['error'] = $message;
    header("Location: ../web/templates/pages/register_page.php");
    exit();
}

// check email duplication
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $message = "Email already registered";
    echo json_encode(["success" => false, "message" => $message]);
    $_SESSION['error'] = $message;
    header("Location: ../web/templates/pages/register_page.php");
    exit();
}
$stmt->close();

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// insert
$sql = "INSERT INTO users (email, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $hashedPassword);

if ($stmt->execute()) {
    $message = "User registered successfully";
    echo json_encode(["success" => true, "message" => $message]);
    $_SESSION['success'] = $message;
    header("Location: ../web/templates/pages/login_page.php");

} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
