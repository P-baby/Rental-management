<?php
// Simple shared database connection.
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "field";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed.");
}

mysqli_set_charset($conn, "utf8mb4");
