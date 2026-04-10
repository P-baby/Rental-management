<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Management</title>
    <link rel="stylesheet" href="../css/adequipment.css">
</head>
<body>
    <div class="container">
        <h1>Equipment Management</h1>
        <a href="addequipment.php" class="btn-add">Add Equipment</a>
        <table class="equipment-table">
        <thead>
            <tr>
                <th>Images</th>
                <th>Equipment ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include __DIR__ . '/database.php';
                $sql = "SELECT * FROM tools";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        $imageName = basename($row['image']);
                        $imageSrc = "../images/" . rawurlencode($imageName);
                        echo "<tr>";
                        echo "<td><img src='".$imageSrc."' alt='".htmlspecialchars($row['tool_name'], ENT_QUOTES)."' width='30%'></td>";
                        echo "<td>".$row['tool_id']."</td>";
                        echo "<td>".$row['tool_name']."</td>";
                        echo "<td>".$row['category']."</td>";
                        echo "<td>".$row['condition_status']."</td>";
                        echo "<td>".$row['quantity']."</td>";
                        echo "<td><a href='editequipment.php?id=".$row['tool_id']."' class='edit-equip-btn'>Edit</a> | 
                                  <a href='action.php?delete_equipment=".$row['tool_id']."' class='delete-equip-btn'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No equipment found</td></tr>";
                }
            ?>
        </tbody>
        </table>
    </div>
</body>
</html>
