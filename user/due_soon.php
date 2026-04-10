<?php
session_start();
include 'connection.php';

header('Content-Type: application/json');

// Return a safe default instead of warnings when the session is missing.
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['due_soon' => 0]);
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT COUNT(*) AS due_soon_count
        FROM rentals
        WHERE user_id = ?
        AND return_datetime IS NULL
        AND due_datetime BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($due_soon_count);
$stmt->fetch();
$stmt->close();

echo json_encode(['due_soon' => (int)$due_soon_count]);
