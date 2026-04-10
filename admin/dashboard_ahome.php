<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title><link rel="stylesheet" href="../css/dashboard_ahome.css">
</head>
<body>
    <?php
include __DIR__ . '/database.php';

$user =$conn->query("SELECT count(*) as total_users FROM users")->fetch_assoc()['total_users'];
$equipment =$conn->query("SELECT SUM(quantity) as total_tools FROM tools")->fetch_assoc()['total_tools'];
$rental_records =$conn->query("SELECT count(*) as total_rental_records FROM rentals")->fetch_assoc()['total_rental_records'];
?>
<div class="dashboard_card_container">
    <div class="card">
        <div class="card_users">
            <h3>Total Users</h3>
            <p><?php echo $user; ?></p>
        </div>
    </div>
    <div class="card">
        <div class="card_equipment">
            <h3>Total Equipments</h3>
            <p><?php echo $equipment; ?></p>
        </div>
    </div>
    <div class="card">      
        <div class="card_rental_records">
            <h3>Total Rental Records</h3>
            <p><?php echo $rental_records; ?></p>
        </div>
    </div>
</div>
</body>
</html>
