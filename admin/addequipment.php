<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add equipment</title>
    <link rel="stylesheet" href="../css/adduser.css">
</head>
<body>
    <div class="wrapper">
        <div class="form-wrapper">
            <h2>Add Equipment</h2>
            <form action="action.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="tool_name">Name</label>
                    <input type="text" name="tool_name"  id="tool_name" class="form-control" required>
                </div>
                <div class="form-group  ">
                    <label for="category">Category</label>
                    <input type="text" name="category"  id="category" class="form-control" required>    
                </div>
                <div class="form-group">
                    <label for="condition_status">Status</label>
                    <input type="text" name="condition_status"  id="condition_status" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity"  id="quantity" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image"  id="image" class="form-control" required>
                </div>
                <button type="submit" name="add_equipment" class="btn">Add Equipment</button>
                <a href="admindashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

</body>
</html>
