<?php

include 'sidebar.php';
include 'connection.php';
$user_id = $_SESSION['user_id'];

//fetch rental history for this current user
$rental_history_query = "SELECT t.name, t.image, r.rent_datetime, r.due_datetime, r.return_datetime FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ?";
$stmt = $conn->prepare($rental_history_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rent_datetime, $due_datetime, $return_datetime);
$rental_history = [];
while ($stmt->fetch()) {
    $rental_history[] = [
        'tool_name' => $tool_name,
        'image' => $image,
        'rent_datetime' => $rent_datetime,
        'due_datetime' => $due_datetime,
        'return_datetime' => $return_datetime
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
    <link rel="stylesheet" href="../css/userdashboard.css">
</head>
<body>
    <div class="userdashboard_content" id="content_area">
        <h1>Rental History</h1>
        <?php if (count($rental_history) > 0): ?>
            <div class="rentals_list">
                <!--inside each rental tool-->
                <p> status: <?php if (!$rental ['return_datetime'] && strtotime($rental['due_datetime']) < time()) {
                    echo "<span style='color: red;'>Overdue</span>";
                } else {
                    echo $rental['return_datetime'] ? "<span style='color: green;'>Returned</span>" : "<span style='color: orange;'>Not returned yet</span>";
                } ?></p>
                <?php foreach ($rental_history as $rental): ?>
                    <div class="rental_item">
                        <img src="../images/<?php echo htmlspecialchars($rental['image']); ?>" alt="<?php echo htmlspecialchars($rental['tool_name']); ?>" class="rental_image">
                        <h3><?php echo htmlspecialchars($rental['tool_name']); ?></h3>
                        <p>Rented on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['rent_datetime']))); ?></p>
                        <p>Due on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['due_datetime']))); ?></p>
                        <p>Returned on: <?php echo $rental['return_datetime'] ? htmlspecialchars(date("F j, Y", strtotime($rental['return_datetime']))) : 'Not returned yet'; ?></p>
                    </div>
                    <!--end of each rental tool-->
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No rental history found.</p>
        <?php endif; ?>
    </div>

</body>
</html>