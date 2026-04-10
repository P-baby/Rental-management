<?php
session_start();
include __DIR__ . '/admin/database.php';

$loginError = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT * FROM users WHERE email='$email' AND username='$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            if ($role === $row['role']) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['role'] = $row['role'];

                if ($row['role'] === "admin") {
                    header("Location: admin/admindashboard.php");
                } else {
                    header("Location: user/userdashboard.php");
                }
                exit();
            }

            $loginError = "Role mismatch.";
        } else {
            $loginError = "Incorrect password.";
        }
    } else {
        $loginError = "Invalid email or username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FieldGear Rental</title>
    <link href="css/login.css" rel="stylesheet">
</head>

<body>
    <div class="logincontainer">
        <div class="loginheader">
            <h1>FGR</h1>
            <p>FieldGear Rental Management</p>
        </div>
        <div class="loginform">
            <form action="login.php" method="post">
                <p>Don't have an account? <a href="signup.php">signup</a></p>

                <div class="role-row">
                    <label for="role">Select Role</label>
                    <select name="role" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <input type="email" name="email" placeholder="Email address" required> <br>
                <input type="text" name="username" placeholder="Username" required> <br>
                <input type="password" name="password" placeholder="password" required> <br>

                <button type="submit" name="login">Login</button>
            </form>

            <?php
           if ($loginError !== '') {
                echo "<p>$loginError</p>";
           }
        ?>
        </div>


</body>

</html>
