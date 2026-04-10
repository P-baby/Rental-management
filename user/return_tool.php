//to update the rentals table set return_date to current date when user clicks return tool button for the specific tool
<?php
session_start();
include 'connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tool_id'])) {
    $tool_id = $_POST['tool_id'];
    $user_id = $_SESSION['user_id'];

    // Update the rentals table to set return_datetime to current date for the specific tool and user
    $update_query = "UPDATE rentals SET return_datetime = CURDATE() WHERE user_id = ? AND tool_id = ? AND return_datetime IS NULL";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $user_id, $tool_id);
    if ($stmt->execute()) {
        // Redirect back to current rentals page after successful return
        header("Location: currentrentals.php");
        exit();
    } else {
        echo "Error returning tool: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
