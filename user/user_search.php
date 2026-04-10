<?php
session_start();
include 'connection.php';

// Keep the partial safe when opened directly.
if (!isset($_SESSION['user_id'])) {
    exit('<p>Please log in again.</p>');
}

$search = trim($_GET['search'] ?? '');
$selected_category = $_GET['category'] ?? '';

$categories = [];
$categoryResult = $conn->query("SELECT DISTINCT category FROM tools ORDER BY category");
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row['category'];
}

$tools = [];
$sql = "SELECT tool_id, tool_name, image, category, condition_status, quantity
        FROM tools
        WHERE 1=1";
$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND tool_name LIKE ?";
    $params[] = "%{$search}%";
    $types .= 's';
}

if ($selected_category !== '') {
    $sql .= " AND category = ?";
    $params[] = $selected_category;
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $tools[] = $row;
}
$stmt->close();
?>

<h2>Search Equipment</h2>

<!-- Submit through loadPage so the results stay inside the content area. -->
<form onsubmit="event.preventDefault(); loadPage('user_search.php?' + new URLSearchParams(new FormData(this)).toString());">
    <input type="text" name="search" placeholder="Search equipment" value="<?php echo htmlspecialchars($search); ?>">
    <select name="category">
        <option value="">Select category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $selected_category === $category ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($category); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Search</button>
</form>

<?php if (!empty($tools)): ?>
    <ul>
        <?php foreach ($tools as $tool): ?>
            <li>
                <h3><?php echo htmlspecialchars($tool['tool_name']); ?></h3>
                <img src="../images/<?php echo rawurlencode(basename($tool['image'])); ?>" alt="<?php echo htmlspecialchars($tool['tool_name']); ?>" width="100">
                <p>Category: <?php echo htmlspecialchars($tool['category']); ?></p>
                <p>Status: <?php echo htmlspecialchars($tool['condition_status']); ?></p>
                <p>Quantity: <?php echo (int)$tool['quantity']; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No equipment found.</p>
<?php endif; ?>

