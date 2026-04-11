<?php
session_start();
include 'connection.php';

// This page is injected into the dashboard content area, so keep it as a partial.
if (!isset($_SESSION['user_id'])) {
    exit('<p>Please log in again.</p>');
}

$user_id = $_SESSION['user_id'];
$rental_history = [];

$sql = "SELECT t.tool_name, t.image, r.rent_datetime, r.due_datetime, r.return_datetime
        FROM rentals r
        JOIN tools t ON r.tool_id = t.tool_id
        WHERE r.user_id = ?
        ORDER BY r.rent_datetime DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rent_datetime, $due_datetime, $return_datetime);

while ($stmt->fetch()) {
    $rental_history[] = [
        'tool_name' => $tool_name,
        'image' => $image,
        'rent_datetime' => $rent_datetime,
        'due_datetime' => $due_datetime,
        'return_datetime' => $return_datetime,
    ];
}
$stmt->close();
?>

<h1>Rental History</h1>

<?php if (!empty($rental_history)): ?>
    <table class="rentals_table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Tool Name</th>
                <th>Rented On</th>
                <th>Due On</th>
                <th>Returned On</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rental_history as $rental): ?>
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
                    <?php echo $rental['return_datetime']
                        ? htmlspecialchars(date("F j, Y", strtotime($rental['return_datetime'])))
                        : 'Not returned yet';
                    ?>
                </td>
                <td>
                    <?php
                    if (!$rental['return_datetime'] && strtotime($rental['due_datetime']) < time()) {
                        echo "<span style='color: red;'>Overdue</span>";
                    } elseif ($rental['return_datetime']) {
                        echo "<span style='color: green;'>Returned</span>";
                    } else {
                        echo "<span style='color: orange;'>Not returned yet</span>";
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No rental history found.</p>
<?php endif; ?>
