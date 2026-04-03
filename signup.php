<?php
include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign up page</title>
    <link href="css/signup.css" rel="stylesheet">
</head>

<body>

    <div class="card">
        <h1>sign up</h1>
        <p>Have An Account?<a href="login.php">Click Here To login</a></p>

        <form action="signup.php" method="post">
            <div class="rows">

                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required> <br>
                <input type="text" name="username" placeholder="Username" required> <br>
                <input type="email" name="email" placeholder="Email address" required> <br>
                <input type="password" name="password" placeholder="password" required> <br>
                <input type="password" name="verify_password" placeholder="verify password" required><br>
            </div>

            <div class="role-row">
                <label for="role">Select Role</label>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" name="signup">Sign up</button>
        </form>

        <?php
                if(isset($_POST['signup'])){
                    $firstname = $_POST['first_name'];
                    $lastname = $_POST['last_name'];
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $verify_password = $_POST['verify_password'];
                    $role = $_POST['role'];

                    if($password != $verify_password){
                        echo "passwords does not match!";
                        exit();
                    }

                    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users(username, first_name, last_name, email, password,role)
                    VALUES('$username', '$firstname', '$lastname', '$email', '$password', '$role')";

                    if(mysqli_query($conn,$sql)){
                        header("location: login.php");
                         exit();
                        echo "error:" . mysqli_error($conn);
                    }
                }
            ?>
    </div>
</body>

</html>