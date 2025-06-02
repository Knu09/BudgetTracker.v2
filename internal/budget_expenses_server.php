<?php
$conn = require_once __DIR__ . '/../internal/db_connection.php';
session_start();
header('Content-Type: application/json');

// Validate login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);

    if (!$type || !$name || $amount <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input.']);
        exit;
    }

    $table = $type === 'budget' ? 'budgets' : ($type === 'expense' ? 'expenses' : null);

    if (!$table) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid type.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO $table (user_id, name, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $user_id, $name, $amount);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => ucfirst($type) . ' added successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert into database.']);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
}
