<?php

session_start();
include 'connection.php';

header('Content-Type: application/json');

// This page is the main content area for the user dashboard. It loads different partials based on user interaction.
// It also includes the profile editing modal and handles the AJAX request to update the profile.
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user_id'];    
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? ''); 
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?,  username = ?, email = ? WHERE user_id = ?");
    $update_stmt->bind_param("ssssi", $first_name, $last_name, $username, $email, $user_id);
    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }
    $update_stmt->close();
    exit();
}
?>