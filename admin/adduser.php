<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="../css/adduser.css">
</head>
<body>
    <div class="wrapper">
        <div class="form-wrapper">
            <h2>Add User</h2>
            <form action="action.php" method="post">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name"  id="first_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name"  id="last_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username"  id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email"  id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role"  id="role" class="form-control" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="btn-group"> 
                <button type="submit" class="btn btn-primary" name="add">Add User</button>
                <a href="admindashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
