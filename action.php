<?php

include 'database.php';

if(isset($_POST['add'])){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (first_name, last_name, username, email, role) VALUES ('$first_name', '$last_name', '$username', '$email', '$role')";
    if(mysqli_query($conn, $sql)){
        header("Location: admindashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if(isset($_POST['edit'])){
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', username='$username', email='$email', role='$role' WHERE user_id=$user_id";
    if(mysqli_query($conn, $sql)){
        header("Location: admindashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if(isset($_GET['delete'])){
    $user_id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE user_id=$user_id";
    if(mysqli_query($conn, $sql)){
        header("Location: admindashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>