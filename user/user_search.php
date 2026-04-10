<?php

include 'connection.php';

//SQL query on your database and stores the result in $tools
$search_query = "SELECT t.name, t.image, r.rental_date, r.due_date, r.return_date FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ? AND (t.name LIKE ? OR r.rental_date LIKE ? OR r.due_date LIKE ?)";
$stmt = $conn->prepare($search_query);
$search_term = '%' . $_GET['search'] . '%';
$stmt->bind_param("isss", $user_id, $search_term, $search_term, $search_term);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rental_date, $due_date, $return_date);
$search_results = [];
while ($stmt->fetch()) {
    $search_results[] = [
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
    <title>search and rent</title>
</head>
<body>

    <div class="userdashboard_content" id="content_area">
        <h2>Search Results</h2>
        <?php if (count($search_results) > 0): ?>
            <ul>
                <?php foreach ($search_results as $result): ?>
                    <li>
                        <img src="<?php echo $result['image']; ?>" alt="<?php echo $result['tool_name']; ?>" width="100">
                        <p><?php echo $result['tool_name']; ?></p>
                        <p>Rental Date: <?php echo $result['rental_date']; ?></p>
                        <p>Due Date: <?php echo $result['due_date']; ?></p>
                        <p>Return Date: <?php echo $result['return_date'] ? $result['return_date'] : 'Not returned yet'; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No results found for "<?php echo htmlspecialchars($_GET['search']); ?>"</p>
        <?php endif; ?>
    </div>
</body>
</html>