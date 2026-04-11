<?php
session_start();
include 'connection.php';
header('Content-Type: application/json');

// Keep returns tied to the signed-in user only.
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in again.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tool_id'])) {
    $tool_id = (int)$_POST['tool_id'];
    $user_id = $_SESSION['user_id'];

    $update_query = "UPDATE rentals
                     SET return_datetime = NOW(), status = 'returned'
                     WHERE user_id = ? AND tool_id = ? AND return_datetime IS NULL";

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $user_id, $tool_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Tool returned successfully.']);
        exit();
    }

    echo json_encode(['success' => false, 'message' => 'Error returning tool: ' . $stmt->error]);
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
