<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>database</title>
</head>
<body>
    <?php
        $db_server = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "equipment";
        $conn = "";

        $conn =mysqli_connect(   $db_server,
                                 $db_user,
                                  $db_pass,
                                  $db_name);
        if($conn){
            echo "connection successful!!!";
        }
        else{
            echo "unsucessful connection";
        }                        
    ?>
    
</body>
</html>
