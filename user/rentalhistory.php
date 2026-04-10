<?php

include 'sidebar.php';
include 'connection.php';
$user_id = $_SESSION['user_id'];

//fetch rental history for this current user
$rental_history_query = "SELECT t.name, t.image, r.rental_date, r.due_date, r.return_date FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ?";
$stmt = $conn->prepare($rental_history_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rental_date, $due_date, $return_date);
$rental_history = [];
while ($stmt->fetch()) {
    $rental_history[] = [
        'tool_name' => $tool_name,
        'image' => $image,
        'rental_date' => $rental_date,
        'due_date' => $due_date,
        'return_date' => $return_date
    ];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental History</title>
</head>
<body>
    <div class="userdashboard_content" id="content_area">
        <h1>Rental History</h1>
        <?php if (count($rental_history) > 0): ?>
            <div class="rentals_list">
                <?php foreach ($rental_history as $rental): ?>
                    <div class="rental_item">
                        <img src="../images/<?php echo htmlspecialchars($rental['image']); ?>" alt="<?php echo htmlspecialchars($rental['tool_name']); ?>" class="rental_image">
                        <h3><?php echo htmlspecialchars($rental['tool_name']); ?></h3>
                        <p>Rented on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['rental_date']))); ?></p>
                        <p>Due on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['due_date']))); ?></p>
                        <p>Returned on: <?php echo $rental['return_date'] ? htmlspecialchars(date("F j, Y", strtotime($rental['return_date']))) : 'Not returned yet'; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No rental history found.</p>
        <?php endif; ?>
    </div>

</body>
</html>