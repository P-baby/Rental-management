<?php
    session_start();
    include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard</title>
    <link rel="stylesheet" href="css/admindash.css">
    <link rel="stylesheet" href="css/dashboard_ahome.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   
    <!-- Chart library for a more professional dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="dashboard_maincontainer">
        <div class="dashboard_sidebar">

            <!--user info-->
            <h2 class="dashboard_userinfo">Hi,Admin</h2>
            <div class="dashboard_sidebar_user">
                <img src="images/admin.jpeg" alt="User Image" class="dashboard_sidebar_user_image">
            </div>

            <div class="dashboard_sidebar_menu">
                <ul class="dashboard_sidebar_menu_list">
                    <li>
                        <a href="#" onclick="loadPage('dashboard_ahome.php')" class="fas fa-dashboard">Dashboard</a>
                    </li>
                    <li>
                        <a href="#" onclick="loadPage('aduser.php')" class="fas fa-users">Users</a>
                    </li>
                    <li>
                        <a href="#" onclick="loadPage('adequipment.php')" class="fas fa-wrench">Equipment</a>
                    </li>
                    <li>
                        <a href="#" onclick="loadPage('adrentalrecords.php')" class="fas fa-history">Rental records</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="dashboard_content_container">
            <div class="dashboard__topnav">
                <a href="#"><i class="fas fa-bars"></i></a>

                <div class="topnav_right"><a href="#"><i class="fas fa-bell"></i></a>
                    <a href="#"><i class="fas fa-power-off"></i>Logout</a>
                </div>
            </div>

            <div class="dashboard_content">
                <div id="dashboard_content_main">
                    <h1>Welcome to Admin Dashboard</h1>
                    <p>Use the sidebar to navigate through different sections of the dashboard.</p>
                   
                   <?php
                        $user =$conn->query("SELECT count(*) as total_users FROM users")->fetch_assoc()['total_users'];
                        $equipment =$conn->query("SELECT SUM(quantity) as total_equipment FROM equipments")->fetch_assoc()['total_equipment'];
                        $rental_records =$conn->query("SELECT count(*) as total_rental_records FROM rentals")->fetch_assoc()['total_rental_records'];
                    ?>
                    <div class="dashboard_card_container">
                        <div class="card">
                            <div class="card_users">
                                <h3>Total Users</h3>
                                <p>
                                    <?php echo $user; ?>
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card_equipment">
                                <h3>Total Equipments</h3>
                                <p>
                                    <?php echo $equipment; ?>
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card_rental_records">
                                <h3>Total Rental Records</h3>
                                <p>
                                    <?php echo $rental_records; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <script>
           function loadPage(page){
            fetch(page)
            .then(res => res.text())
         .then(data => {
            document.getElementById("dashboard_content_main").innerHTML = data;
         });
           }
        </script>

    </div>
    
</body>

</html>