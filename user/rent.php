<?php
session_start();
include 'connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Please log in.']);
    } else {
        echo '<div class="error">Please log in again.</div>';
    }
    exit();
}

$user_id = $_SESSION['user_id'];
$tool_id = $_GET['tool_id'] ?? $_POST['tool_id'] ?? null;

if (!$tool_id) {
    echo '<div class="error">Search for a tool first and click the rent button.</div>';
    exit();
}

// If a GET request, show confirmation card with details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch tool info
    $stmt = $conn->prepare("SELECT tool_name, image, category, condition_status, quantity FROM tools WHERE tool_id = ?");
    $stmt->bind_param("i", $tool_id);
    $stmt->execute();
    $stmt->bind_result($tool_name, $image, $category, $condition_status, $quantity);
    if (!$stmt->fetch()) {
        $stmt->close();
        echo '<div class="error">Tool not found.</div>';
        exit();
    }
    $stmt->close();

    // Count currently rented
    $stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE tool_id = ? AND return_datetime IS NULL");
    $stmt->bind_param("i", $tool_id);
    $stmt->execute();
    $stmt->bind_result($currently_rented);
    $stmt->fetch();
    $stmt->close();

    $available = $quantity - (int)$currently_rented;

    if ($available <= 0) {
        echo '<div class="error">Sorry, this tool is currently completely rented out.</div>';
        exit();
    }
    ?>
    <div class="rent_confirm_card">
        <h2> <?php echo htmlspecialchars($tool_name); ?></h2>
        <img src="../images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($tool_name); ?>" style="width:100px; border-radius:10px; margin-bottom:10px;">
        <div class="tool_details">
            <div><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></div>
            <div><strong>Status:</strong> <?php echo htmlspecialchars($condition_status); ?></div>
            <div><strong>Available:</strong> <?php echo (int)$available; ?></div>
        </div>
        <form method="POST" action="rent.php" id="rentForm" style="margin-top:18px;">
            <input type="hidden" name="tool_id" value="<?php echo (int)$tool_id; ?>">
            <button type="submit" class="confirm_button">Yes, Rent this tool</button>
            <button type="button" onclick="window.location.href='userdashboard.php';" class="cancel_button">Cancel</button>
        </form>
        <div id="rentResult"></div>
    </div>
    <style>
    .rent_confirm_card {background:#fff; padding:28px 26px; border-radius:12px; max-width:340px; margin:30px auto; box-shadow:0 2px 12px #0002; text-align:center;}
    .rent_confirm_card img {box-shadow:0 2px 14px #0001;}
    .tool_details {margin-bottom:12px;}
    .confirm_button, .cancel_button {display:inline-block;margin:8px 5px 0 5px;padding:8px 20px; border:none; border-radius:5px;font-weight:500;font-size:1em; cursor:pointer; background:#2d5be3; color:#fff;transition:background .2s;}
    .cancel_button {background:#ddd;color:#333;}
    .confirm_button:hover {background:#174dbe;}
    .cancel_button:hover {background:#bbb;}
    .error {color: #a00; text-align:center; background:#fff0f0; padding:12px 20px; border-radius:8px;}
    </style>
    <?php
    exit();
}

// If a POST request, process renting tool
header('Content-Type: application/json');

// Prevent user from renting more than allowed active rentals
$allowed_rentals = 3;
$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_datetime IS NULL");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($active_rentals);
$stmt->fetch();
$stmt->close();

if ($active_rentals >= $allowed_rentals) {
    echo json_encode(['success' => false, 'message' => "You have already rented $allowed_rentals tools. Please return one before renting another."]);
    exit();
}

// Check tool still available (in case of race)
$stmt = $conn->prepare("SELECT quantity FROM tools WHERE tool_id = ?");
$stmt->bind_param("i", $tool_id);
$stmt->execute();
$stmt->bind_result($quantity);
if (!$stmt->fetch() || $quantity <= 0) {
    $stmt->close();
    echo json_encode(['success'=>false,'message'=>'Tool not found or not available.']);
    exit();
}
$stmt->close();
$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE tool_id = ? AND return_datetime IS NULL");
$stmt->bind_param("i", $tool_id);
$stmt->execute();
$stmt->bind_result($currently_rented);
$stmt->fetch();
$stmt->close();
if (($quantity - (int)$currently_rented) <= 0) {
    echo json_encode(['success'=>false, 'message'=>'Sorry, this tool is fully rented out.']);
    exit();
}

// Prevent renting same tool again without return
$stmt = $conn->prepare("SELECT 1 FROM rentals WHERE user_id = ? AND tool_id = ? AND return_datetime IS NULL");
$stmt->bind_param("ii", $user_id, $tool_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    echo json_encode(['success'=>false, 'message'=>'You are already renting this tool!']);
    exit();
}
$stmt->close();

// Insert new rental (default 7 days)
$stmt = $conn->prepare("INSERT INTO rentals (user_id, tool_id, rent_datetime, due_datetime) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY))");
$stmt->bind_param("ii", $user_id, $tool_id);

if ($stmt->execute()) {
    echo json_encode(['success'=>true, 'message'=>'Rented successfully!']);
} else {
    echo json_encode(['success'=>false, 'message'=>'Database error while renting tool.']);
}
$stmt->close();
exit();