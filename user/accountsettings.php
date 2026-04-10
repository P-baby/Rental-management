<?php
session_start();
include 'connection.php';

// Simple profile partial for the dashboard content area.
if (!isset($_SESSION['user_id'])) {
    exit('<p>Please log in again.</p>');
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT first_name, last_name, username, email, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<h1>Account Settings</h1>
<p>Name: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
<p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
<p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
