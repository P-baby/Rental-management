<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sidebar</title>
    <link rel="stylesheet" href="../css/userdashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/
</head>
<body>
    <div class="userdashboard_sidebar">
            <h2 class="userdashboard_userinfo">Hi, <?php echo htmlspecialchars($email); ?></h2>
            <div class="userdashboard_sidebar_user">
                <img src="../images/user.jpeg" alt="User Image" class="userdashboard_sidebar_user_image">
            </div>
            <div class="userdashboard_sidebar_menu">
                <ul class="userdashboard_sidebar_menu_list">
                    <li><a href="#" onclick="loadPage('userdashboard.php')" class="fas fa-dashboard">Dashboard</a></li>
                    <li><a href="#" onclick="loadPage('user_search.php')" class="fas fa-search">Search equipments</a></li>
                    <li ><a href="#" onclick="loadPage('rent.php')" class="fas fa-shopping-cart">Rent Equipment</a></li>
                    <li><a href="#" onclick="loadPage('currentrentals.php')" class="fas fa-wrench">Current rentals</a></li>
                    <li><a href="#" onclick="loadPage('rentalhistory.php')" class="fas fa-history">Rental history</a></li>
                    <li><a href="#" onclick="loadPage('accountsettings.php')" class="fas fa-user">Profile</a></li>
                    <li><a href="../logout.php" class="fas fa-power-off">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>