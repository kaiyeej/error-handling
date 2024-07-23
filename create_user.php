<?php
// Database connection
include 'config.php';

$username = 'admin';
$password = 'Admin123!';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO tbl_users (username, password) VALUES (?, ?)";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
    mysqli_stmt_execute($stmt);
    echo "User created successfully.";
    mysqli_stmt_close($stmt);
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
