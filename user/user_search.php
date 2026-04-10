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
        <form method="GET" action="user_search.php">
            <input type="hidden" name="search" placeholder="Search Equipment" value="<?php echo $result['tool_name']; ?>">
                <select name="category">
                    <option value="">Select category</option>
                    <?php foreach ($categories as $category): ?>
                     <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                    <?php endforeach; ?>
                </select>
            <button type="submit">Rent this tool</button>
        </form>    
    </div>
</body>
</html>
<?php

include 'connection.php';

//SQL query to fetch search results based on user input
$search_query = "SELECT tool_id, tool_name, image, rental_date, due_date, return_datetime FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ? AND (t.name LIKE ? OR r.rental_date LIKE ? OR r.due_datetime LIKE ?)";
$stmt = $conn->prepare($search_query);
$search_term = '%' . $_GET['search'] . '%';
$stmt->bind_param("isss", $user_id, $search_term, $search_term, $search_term);
$stmt->execute();
$stmt->bind_result($tool_id, $tool_name, $image, $rental_date, $due_datetime, $return_datetime);
$search_results = [];
while ($stmt->fetch()) {
    $search_results[] = [
        'tool_id' => $tool_id,
        'tool_name' => $tool_name,
        'image' => $image,
        'rental_date' => $rental_date,
        'due_date' => $due_datetime,
        'return_date' => $return_datetime
    ];
}
$stmt->close();

//fetch categories for dropdown
$categories_query = "SELECT DISTINCT category FROM tools";
$categories_result = $conn->query($categories_query);
$categories = [];
if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

<ul>
    <?php foreach ($search_results as $result): ?>
        <li>
            <h3><?php echo htmlspecialchars($result['tool_name']); ?></h3>
            <img src="<?php echo htmlspecialchars($result['image']); ?>" alt="<?php echo htmlspecialchars($result['tool_name']); ?>" width="100">
            <p>Rental Date: <?php echo htmlspecialchars($result['rental_date']); ?></p>
            <p>Due Date: <?php echo htmlspecialchars($result['due_date']); ?></p>
            <p>Return Date: <?php echo htmlspecialchars($result['return_date']); ?></p>
        </li>
    <?php endforeach; ?>
</ul>
?>
