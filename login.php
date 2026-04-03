<?php
include 'database.php';
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
           if(isset($_POST['login'])){
             $email = $_POST['email'];
             $username = $_POST['username'];
             $password = $_POST['password'];
             $role = $_POST['role'];

                $sql = "SELECT * FROM users WHERE email='$email' AND username='$username        '";
                $result = $conn->query($sql);

                if ($result->num_rows == 1) {
                    $row = mysqli_fetch_assoc($result);

                if (password_verify($password,$row['password'])){

                if($role ==$row['role']){
                    $_SESSION['userid'] = $row['userid'];
                    $_SESSION['firstname'] = $row['firstname'];
                    $_SESSION['ProfilePicture'] = $row['ProfilePicture'];
                    $_SESSION['role'] = $row['role'];

                if($row['role'] =="admin"){
                    header("location:admindashboard.php");
                }
                else{
                    header("location:user/userdashboard.php");
                }
                exit();
                }
                else{
                echo "role mismatch";
                }
                }
                else{
                echo"inccorect password";
                }
            }    
           }
        ?>
        </div>


</body>

</html>