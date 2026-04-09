<?php

include 'database.php';

if(isset($_GET['id'])){
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id=$user_id";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $username = $row['username'];
        $email = $row['email'];
        $role = $row['role'];
    } else {
        echo "User not found";
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
    <title>Edit User</title>
    <link rel="stylesheet" href="css/adduser.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="form-wrapper">
            <h2>Edit User</h2>
            <form action="action.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name"  id="first_name" class="form-control" value="<?php echo $first_name; ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name"  id="last_name" class="form-control" value="<?php echo $last_name; ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username"  id="username" class="form-control" value="<?php echo $username; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email"  id="email" class="form-control" value="<?php echo $email; ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role"  id="role" class="form-control" required>
                        <option value="admin" <?php if($role == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="user" <?php if($role == 'user') echo 'selected'; ?>>User</option>
                    </select>
                </div>
                <div class="btn-group"> 
                <button type="submit" class="btn btn-primary" name="edit">Update User</button>
                <a href="admindashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>