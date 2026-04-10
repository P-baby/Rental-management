<?php

include __DIR__ . '/database.php';

if(isset($_GET['id'])){
    $tool_id = $_GET['id'];
    $sql = "SELECT * FROM tools WHERE tool_id=$tool_id";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $tool_name = $row['tool_name'];
        $category = $row['category'];
        $condition_status = $row['condition_status'];
        $quantity = $row['quantity'];
        $image = $row['image'];
    } else {
        echo "Equipment not found";
        exit;
    }
} else {
    echo "Invalid request";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment</title>
    <link rel="stylesheet" href="../css/adduser.css">
</head>
<body>
    <div class ="wrapper">
        <div class="form-wrapper">
            <h2>Edit Equipment</h2>
            <form action="action.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tool_id" value="<?php echo $tool_id; ?>">
                <div class="form-group">
                    <label for="tool_name">Name</label>
                    <input type="text" name="tool_name"  id="tool_name" class="form-control" value="<?php echo $tool_name; ?>" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" name="category"  id="category" class="form-control" value="<?php echo $category; ?>" required>       
                </div>
                <div class="form-group">
                    <label for="condition_status">Status</label>
                    <input type="text" name="condition_status"  id="condition_status" class="form-control" value="<?php echo $condition_status; ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity"  id="quantity" class="form-control" value="<?php echo $quantity; ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image"  id="image" class="form-control">
                    <img src="../images/<?php echo rawurlencode(basename($image)); ?>" alt="" width="30%">
                </div>
                <button type="submit" name="edit_equipment" class="btn">Update Equipment</button>
                <a href="adequipment.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
