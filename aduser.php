<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel ="stylesheet" href="css/aduser.css">
</head>
<body>
    <div class="container">
       <h2>User management</h2>
       <a href="adduser.php" class="btn-add">Add user</a>

       <table class="table table-striped">
           <thead>
               <tr>
                   <th>User ID</th>
                   <th>First Name</th>
                   <th>Last Name</th>
                   <th>Username</th>
                   <th>Email</th>
                   <th>Role</th>
                   <th>Actions</th>
               </tr>
           </thead>
           <tbody>
               <?php
                    include 'database.php';
                    $sql = "SELECT * FROM users";
                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>".$row['user_id']."</td>";
                            echo "<td>".$row['first_name']."</td>";
                            echo "<td>".$row['last_name']."</td>";
                            echo "<td>".$row['username']."</td>";
                            echo "<td>".$row['email']."</td>";
                            echo "<td>".$row['role']."</td>";
                            echo "<td>
                                    <a href='edituser.php?id=".$row['user_id']."' class='edit-btn'>Edit</a>
                                    <a href='action.php?delete=".$row['user_id']."' class='btn-delete'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No users found</td></tr>";
                    }
               ?>
           </tbody>
       </table>     
    </div>
</body>
</html>