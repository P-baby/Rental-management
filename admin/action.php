<?php

include __DIR__ . '/database.php';

if(isset($_POST['add'])){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (first_name, last_name, username, email, role) VALUES ('$first_name', '$last_name', '$username', '$email', '$role')";
    if(mysqli_query($conn, $sql)){
        header("Location: admindashboard.php");
        exit;
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
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if(isset($_GET['delete'])){
    $user_id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE user_id=$user_id";
    if(mysqli_query($conn, $sql)){
        header("Location: admindashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['add_equipment'])){
    $tool_name = $_POST['tool_name'];
    $category = $_POST['category'];
    $condition_status = $_POST['condition_status'];
    $quantity = $_POST['quantity'];
    $image = basename($_FILES['image']['name']);
    $target = dirname(__DIR__) . '/images/' . $image;

    $sql = "INSERT INTO tools (tool_name, category, condition_status, quantity, image) VALUES ('$tool_name', '$category', '$condition_status', '$quantity', '$image')";
    if(mysqli_query($conn, $sql)){
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            header("Location: admindashboard.php");
            exit;
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['edit_equipment'])){
    $tool_id = $_POST['tool_id'];
    $tool_name = $_POST['tool_name'];
    $category = $_POST['category'];
    $condition_status = $_POST['condition_status'];
    $quantity = $_POST['quantity'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image = basename($_FILES['image']['name']);
        $target = dirname(__DIR__) . '/images/' . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $sql = "UPDATE tools SET tool_name='$tool_name', category='$category', condition_status='$condition_status', quantity='$quantity', image='$image' WHERE tool_id=$tool_id";
    } else {
        $sql = "UPDATE tools SET tool_name='$tool_name', category='$category', condition_status='$condition_status', quantity='$quantity' WHERE tool_id=$tool_id";
    }

    if(mysqli_query($conn, $sql)){
        header("Location: admindashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if(isset($_GET['delete_equipment'])){
    $tool_id = $_GET['delete_equipment'];
    $sql = "DELETE FROM tools WHERE tool_id=$tool_id";
    if(mysqli_query($conn, $sql)){
        header("Location: admindashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
