<?php

include 'connection.php';
$user_id = $_SESSION['user_id'];

//fetch current rentals for this current user
$current_rentals_query = "SELECT t.name, t.image, r.rent_datetime, r.due_datetime FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ? AND r.return_datetime IS NULL";
$stmt = $conn->prepare($current_rentals_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rent_datetime, $due_datetime);
$current_rentals = [];
while ($stmt->fetch()) {
    $current_rentals[] = [  
        'tool_name' => $tool_name,
        'image' => $image,
        'rent_datetime' => $rent_datetime,
        'due_datetime' => $due_datetime
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

        <!--inside each rental item button to return the tool-->
        <form method="POST" action="return_tool.php">
            <input type="hidden" name="tool_id" value="<?php echo htmlspecialchars($rental['tool_id']); ?>">
            <button type="submit">Return this tool</button>
        </form>
        <?php if (count($current_rentals) > 0): ?>
            <div class="rentals_list">
                <?php foreach ($current_rentals as $rental): ?>
                    <div class="rental_item">
                        <img src="../images/<?php echo htmlspecialchars($rental['image']); ?>" alt="<?php echo htmlspecialchars($rental['tool_name']); ?>" class="rental_image">
                        <h3><?php echo htmlspecialchars($rental['tool_name']); ?></h3>
                        <p>Rented on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['rent_datetime']))); ?></p>
                        <p>Due on: <?php echo htmlspecialchars(date("F j, Y", strtotime($rental['due_datetime']))); ?></p>
                        <form method="POST" action="return_tool.php">
                            <input type="hidden" name="tool_id" value="<?php echo htmlspecialchars($rental['tool_id']); ?>">
                            <button type="submit">Return this tool</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No current rentals found.</p>
        <?php endif; ?>
            <p>No current rentals found.</p>
        <?php endif; ?>

    </div>
</body>
</html>