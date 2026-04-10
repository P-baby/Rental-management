<?php

include 'connection.php';
$user_id = $_SESSION['user_id'];

//fetch current rentals for this current user
$current_rentals_query = "SELECT t.name, t.image, r.rental_date, r.due_date FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ? AND r.return_date IS NULL";
$stmt = $conn->prepare($current_rentals_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rental_date, $due_date);
$current_rentals = [];
while ($stmt->fetch()) {
    $current_rentals[] = [  
        'tool_name' => $tool_name,
        'image' => $image,
        'rental_date' => $rental_date,
        'due_date' => $due_date
    ];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Rentals</title>
    <link rel="stylesheet" href="../css/userdashboard.css">

</head>
<body>
    <div class="userdashboard_content" id="content_area">
        <h1>Current Rentals</h1>
        <?php if (count($current_rentals) > 0): ?>
            <div class="rentals_list">
                <?php foreach ($current_rentals as $rental): ?>
                    <div class="rental_item">
                        <img src="../images/<?php echo htmlspecialchars($rental['image']); ?>" alt="<?php echo htmlspecialchars($rental['tool_name']); ?>" class="rental_image">
                        <h3><?php echo htmlspecialchars($rental['tool_name']); ?></h3>
                        <p>Rented on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['rental_date']))); ?></p>
                        <p>Due on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['due_date']))); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No current rentals found.</p>
        <?php endif; ?>

    </div>
</body>
</html>