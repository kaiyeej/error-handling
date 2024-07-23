<?php
$conn = mysqli_connect("localhost", "root", '', "error_handling_db");
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    die('<div class="alert alert-danger" role="alert">Database connection failed. Please try again later.</div>');
}
?>
