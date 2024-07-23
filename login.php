<?php
session_start(); // Start a session

// Database connection
include 'config.php';

// Custom error handler function
function customError($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno] in $errfile on line $errline: $errstr");
    echo '<div class="alert alert-danger" role="alert">An error occurred. Please try again later.</div>';
    return true;
}

set_error_handler('customError');
ini_set('display_errors', '0'); // Disable error display to users
error_reporting(E_ALL); // Report all errors

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $inputPassword = htmlspecialchars(trim($_POST['password']), ENT_QUOTES, 'UTF-8');

    if (empty($inputUsername) || empty($inputPassword)) {
        $errorMsg = '<div class="alert alert-danger" role="alert">Please fill in both fields.</div>';
    } else {
        $sql = "SELECT id, username, password FROM tbl_users WHERE username = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $inputUsername);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($inputPassword, $user['password'])) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            } else {
                $errorMsg = '<div class="alert alert-danger" role="alert">Invalid username or password.</div>';
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log("Error preparing query: " . mysqli_error($conn));
            $errorMsg = '<div class="alert alert-danger" role="alert">An error occurred. Please try again later.</div>';
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errorMsg)): ?>
                            <?php echo $errorMsg; ?>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username" autocomplete="off" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" autocomplete="off" class="form-control" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
