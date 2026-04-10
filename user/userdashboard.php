<?php

session_start();
include 'connection.php';

//get user id from session
$user_id = $_SESSION['user_id'];
// Fetch user information
$user_query = "SELECT email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($email);
$stmt->fetch();
$stmt->close();

//card queries
$available = "SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_date IS NULL"->fetch_assoc()['available_count'];
$stmt = $conn->prepare($available);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($available_count);
$stmt->fetch();
$stmt->close();

$total_rentals = "SELECT COUNT(*) FROM rentals WHERE user_id = ?")->fetch_assoc()['total_rentals'];
$stmt = $conn->prepare($total_rentals);
$stmt->bind_param("i", $user_id);
$stmt->execute();   
$stmt->bind_result($total_rentals_count);
$stmt->fetch(); 
$stmt->close();

$overdue = "SELECT COUNT(*) FROM rentals WHERE user_id = ? AND return_date IS NULL AND due_date < CURDATE()")->fetch_assoc()['overdue_count'];
$stmt = $conn->prepare($overdue);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($overdue_count);
$stmt->fetch();
$stmt->close();

//search and filter queries

$filter_query = "SELECT t.name, t.image, r.rental_date, r.due_date, r.return_date FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ? AND r.rental_date >= ? AND r.rental_date <= ?";
$stmt = $conn->prepare($filter_query);  
$stmt->bind_param("iss", $user_id, $_GET['start_date'], $_GET['end_date']);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rental_date, $due_date, $return_date);
$filter_results = [];
while ($stmt->fetch()) {
    $filter_results[] = [
        'tool_name' => $tool_name,
        'image' => $image,
        'rental_date' => $rental_date,
        'due_date' => $due_date,
        'return_date' => $return_date
    ];
}
$stmt->close();

$tools_query = "SELECT t.name, t.image, r.rental_date, r.due_date, r.return_date FROM rentals r JOIN tools t ON r.tool_id = t.tool_id WHERE r.user_id = ?";
$stmt = $conn->prepare($tools_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tool_name, $image, $rental_date, $due_date, $return_date);
$tools = [];
while ($stmt->fetch()) {
    $tools[] = [
        'tool_name' => $tool_name,
        'image' => $image,
        'rental_date' => $rental_date,
        'due_date' => $due_date,
        'return_date' => $return_date
    ];
}
$stmt->close();

//unique categories for filter dropdown
$categories_query = "SELECT DISTINCT category FROM tools";
$result = $conn->query($categories_query);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
}
$result->free();
 

?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User dashboard</title>
    <link rel="stylesheet" href="../css/userdashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="userdashboard_maincontainer">
        <?php include 'sidebar.php'; ?>
        <div class="userdashboard_content_container">
            <div class="userdashboard__topnav">
                <span class="userdashboard_welcome">Welcome to your dashboard, <?php echo htmlspecialchars($first_name); ?>!</span>
            
                <div class="topnav_right"><a href="#"><i class="fas fa-bell"></i></a></div>
            </div>
            <div class="userdashboard_content" id="content_area">
                <!-- Content will be loaded here via AJAX -->
            </div>
        </div>
        <div class="userdashboard_footer">
            <p>&copy; 2024 FieldGear. All rights reserved.</p>
        </div>
    </div>
// JavaScript for AJAX content loading
    <script>
        function loadPage(page) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', page, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('content_area').innerHTML = this.responseText;
                } else {
                    document.getElementById('content_area').innerHTML = '<p>Error loading page.</p>';
                }
            };
            xhr.send();
        }

        // Load the dashboard home by default
        loadPage('userdashboard.php');

        //highlight active nav
        const navLinks = document.querySelectorAll('.userdashboard_sidebar_menu_list a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>