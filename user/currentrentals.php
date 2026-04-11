<?php
session_start();
include 'connection.php';

// Guard direct access so the partial only runs for signed-in users.
if (!isset($_SESSION['user_id'])) {
    exit('<p>Please log in again.</p>');
}

$user_id = $_SESSION['user_id'];
$current_rentals = [];

// Select only the fields this partial actually renders.
$sql = "SELECT t.tool_id, t.tool_name, t.image, r.rent_datetime, r.due_datetime
        FROM rentals r
        JOIN tools t ON r.tool_id = t.tool_id
        WHERE r.user_id = ? AND r.return_datetime IS NULL
        ORDER BY r.rent_datetime DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tool_id, $tool_name, $image, $rent_datetime, $due_datetime);

while ($stmt->fetch()) {
    $current_rentals[] = [
        'tool_id' => $tool_id,
        'tool_name' => $tool_name,
        'image' => $image,
        'rent_datetime' => $rent_datetime,
        'due_datetime' => $due_datetime,
    ];
}
$stmt->close();
?>

<h1>Current Rentals</h1>

<?php if (!empty($current_rentals)): ?>
    <table class="rentals_table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Tool Name</th>
                <th>Rented On</th>
                <th>Due On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($current_rentals as $rental): ?>
            <tr>
                <td>
                    <img src="../images/<?php echo rawurlencode(basename($rental['image'])); ?>"
                         alt="<?php echo htmlspecialchars($rental['tool_name']); ?>"
                         style="width:60px; border-radius:5px;">
                </td>
                <td><?php echo htmlspecialchars($rental['tool_name']); ?></td>
                <td><?php echo htmlspecialchars(date("F j, Y", strtotime($rental['rent_datetime']))); ?></td>
                <td><?php echo htmlspecialchars(date("F j, Y", strtotime($rental['due_datetime']))); ?></td>
                <td>
                    <form method="POST" action="return_tool.php" class="return_form">
                        <input type="hidden" name="tool_id" value="<?php echo (int)$rental['tool_id']; ?>">
                        <button type="submit" class="return_btn">Return</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No current rentals found.</p>
<?php endif; ?>
