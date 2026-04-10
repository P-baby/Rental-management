<?php
session_start();
include __DIR__ . '/database.php';

// Cards and chart: summary queries
$userCount = $conn->query("SELECT count(*) as total_users FROM users")->fetch_assoc()['total_users'];
$equipmentCount = $conn->query("SELECT SUM(quantity) as total_tools FROM tools")->fetch_assoc()['total_tools'];
$rentalCount = $conn->query("SELECT count(*) as total_rental_records FROM rentals")->fetch_assoc()['total_rental_records'];

// Pie chart: equipment by category/type
$equipmentLabels = [];
$equipmentData = [];
$result = $conn->query("SELECT category, SUM(quantity) as total FROM tools GROUP BY category");
while ($row = $result->fetch_assoc()) {
    $equipmentLabels[] = $row['category'];
    $equipmentData[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard</title>
    <link rel="stylesheet" href="../css/admindash.css">
    <link rel="stylesheet" href="../css/dashboard_ahome.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="dashboard_maincontainer">
    <div class="dashboard_sidebar">
        <h2 class="dashboard_userinfo">Hi, <?php if (isset($_SESSION['first_name'])) { echo htmlspecialchars($_SESSION['first_name']); } else { echo "Admin"; } ?></h2>
        <div class="dashboard_sidebar_user">
            <img src="../images/admin.jpeg" alt="User Image" class="dashboard_sidebar_user_image">
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
                <a href="../logout.php"><i class="fas fa-power-off"></i>Logout</a>
            </div>
        </div>
        <div class="dashboard_content">
            <div id="dashboard_content_main">

                <!-- Summary Cards at the Top -->
                <div class="dashboard_card_container">
                    <div class="card">
                        <div class="card_users">
                            <h3>Total Users</h3>
                            <p><?php echo $userCount; ?></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card_equipment">
                            <h3>Total Equipments</h3>
                            <p><?php echo $equipmentCount; ?></p>
                        </div>
                    </div>
                    <div class="card">      
                        <div class="card_rental_records">
                            <h3>Total Rental Records</h3>
                            <p><?php echo $rentalCount; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Charts row -->
                <div class="dashboard_charts">

                  <!-- PIE CHART: Users, Equipments, Rentals -->
                  <div class="chart_container">
                    <canvas id="overviewPie"></canvas>
                    <p style="text-align:center;margin-top:10px;">Overall distribution of users, tools, and rentals in the system.</p>
                  </div>

                  <!-- PIE CHART: Equipment by Category -->
                  <div class="chart_container">
                    <canvas id="equipmentPie"></canvas>
                    <p style="text-align:center;margin-top:10px;">Distribution of equipment based on category/type.</p>
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

// Data for JS
const overviewLabels = ['Users', 'Tools', 'Rentals'];
const overviewData = [
    <?php echo $userCount; ?>,
    <?php echo $equipmentCount; ?>,
    <?php echo $rentalCount; ?>
];
const equipmentLabels = [<?php echo '"' . implode('","', $equipmentLabels) . '"'; ?>];
const equipmentData = [<?php echo implode(',', $equipmentData); ?>];
const pieColors = [
    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#00b894', '#fdcb6e', '#d63031'
];

new Chart(document.getElementById('overviewPie').getContext('2d'), {
    type: 'pie',
    data: {
        labels: overviewLabels,
        datasets: [{
            data: overviewData,
            backgroundColor: pieColors,
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: {size: 15, weight: 'bold'} }
            },
            title: {
                display: true,
                text: 'Rental Management System Overview',
                font: {weight: 'bold', size: 20}
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        const label = ctx.label || '';
                        const value = ctx.parsed || 0;
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const percent = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} (${percent}%)`;
                    }
                }
            }
        }
    }
});

new Chart(document.getElementById('equipmentPie').getContext('2d'), {
    type: 'pie',
    data: {
        labels: equipmentLabels,
        datasets: [{
            data: equipmentData,
            backgroundColor: pieColors.slice(0, equipmentLabels.length),
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: {size: 15, weight: 'bold'} }
            },
            title: {
                display: true,
                text: 'Equipment Distribution by Category',
                font: {weight: 'bold', size: 20}
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        const label = ctx.label || '';
                        const value = ctx.parsed || 0;
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const percent = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} (${percent}%)`;
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>
