<?php
// Ensure session_start() is called only once and at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../internal/db_connection.php'; // $conn should be a MySQLi object

header('Content-Type: application/json');
$response = ['success' => false]; // Default response

// Validate login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    $response['error'] = 'User not authenticated.';
    echo json_encode($response);
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add'; // Default action to 'add' if not specified
    $type = $_POST['type'] ?? null;

    // Determine table name early
    $table = null;
    if ($type === 'budget') {
        $table = 'budget'; // Singular table name
    } elseif ($type === 'expense') {
        $table = 'expense'; // Singular table name
    }

    if (!$table) {
        http_response_code(400);
        $response['error'] = 'Invalid type specified.';
        echo json_encode($response);
        exit;
    }

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT); // Use filter_var for float

        if (empty($name) || $amount === false || $amount <= 0) {
            http_response_code(400);
            $response['error'] = 'Invalid name or amount for adding.';
            echo json_encode($response);
            exit;
        }

        $sql = "INSERT INTO $table (user_id, name, amount) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            http_response_code(500);
            // For debugging: $response['error'] = 'Failed to prepare statement: ' . $conn->error;
            $response['error'] = 'Failed to prepare statement for adding.';
            echo json_encode($response);
            exit;
        }

        // For MySQLi: 'i' for integer, 's' for string, 'd' for double (float)
        $stmt->bind_param("isd", $user_id, $name, $amount);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = ucfirst($type) . ' added successfully.';
            } else {
                // For debugging: $response['error'] = 'No rows affected, insert failed: ' . $stmt->error;
                $response['error'] = 'Failed to add ' . $type . ' (no rows affected).';
            }
        } else {
            http_response_code(500);
            // For debugging: $response['error'] = 'Failed to execute statement: ' . $stmt->error;
            $response['error'] = 'Failed to execute statement for adding.';
        }
        $stmt->close();

    } elseif ($action === 'remove') {
        $id_to_remove = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if ($id_to_remove === false || $id_to_remove === null) { // filter_var returns false on failure
            http_response_code(400);
            $response['error'] = 'Invalid ID for removal.';
            echo json_encode($response);
            exit;
        }

        $sql = "DELETE FROM $table WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            http_response_code(500);
            // For debugging: $response['error'] = 'Failed to prepare statement: ' . $conn->error;
            $response['error'] = 'Failed to prepare statement for removal.';
            echo json_encode($response);
            exit;
        }

        // For MySQLi: 'i' for integer
        $stmt->bind_param("ii", $id_to_remove, $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = ucfirst($type) . ' removed successfully.';
            } else {
                $response['error'] = ucfirst($type) . ' not found or you do not have permission to remove it (no rows affected).';
            }
        } else {
            http_response_code(500);
            // For debugging: $response['error'] = 'Failed to execute statement: ' . $stmt->error;
            $response['error'] = 'Failed to execute statement for removal.';
        }
        $stmt->close();

    } else {
        http_response_code(400);
        $response['error'] = 'Invalid action specified.';
    }

} else {
    http_response_code(405);
    $response['error'] = 'Method not allowed.';
}

if (isset($conn) && $conn instanceof mysqli) { // Check if $conn is a valid mysqli object before closing
    $conn->close();
}

echo json_encode($response);
?>
