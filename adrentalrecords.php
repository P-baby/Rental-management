<?php

session_start();
include 'database.php';

// Fetch all rental records with user and tool details for admin dashboard display
$sql = "SELECT 
    rentals.rental_id,
    rentals.user_id,
    users.username AS user_name,
    tools.tool_name,
    rentals.rent_datetime,
    rentals.due_datetime,
    rentals.return_datetime,
    rentals.status
FROM rentals
JOIN tools ON rentals.tool_id = tools.tool_id
JOIN users ON rentals.user_id = users.user_id";

$result = mysqli_query($conn, $sql);
$rentals = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rentals[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Records</title>
    <link rel="stylesheet" href="css/usera.css">
</head>
<body>
    <div class="rental-records-container">
        <h2>Rental Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Rental ID</th>
                    <th>User</th>
                    <th>Tool</th>
                    <th>Rental Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rentals as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['rental_id']); ?></td>
                    <td><?php echo htmlspecialchars($record['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['tool_name']); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($record['rent_datetime'])); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($record['due_datetime'])); ?></td>
                    <td><?php echo $record['return_datetime'] ? date('Y-m-d H:i:s', strtotime($record['return_datetime'])) : '-'; ?></td>
                    <td><?php echo htmlspecialchars($record['status']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($rentals)): ?>
                    <tr>
                        <td colspan="7">No rental records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
</body>
</html>