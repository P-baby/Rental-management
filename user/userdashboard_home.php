<?php
session_start();
include 'connection.php';

// Default dashboard partial loaded into #content_area.
if (!isset($_SESSION['user_id'])) {
    exit('<p>Please log in again.</p>');
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_datetime IS NULL");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($active_count);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_rentals_count);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_datetime IS NULL AND due_datetime < NOW()");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($overdue_count);
$stmt->fetch();
$stmt->close();
?>

<h1>Dashboard Overview</h1>
<p>Active rentals: <?php echo (int)$active_count; ?></p>
<p>Total rentals: <?php echo (int)$total_rentals_count; ?></p>
<p>Overdue rentals: <?php echo (int)$overdue_count; ?></p>
