<?php
// Created and Programmed by: LeoTechWhiz / Roshan Gautam


// Initialize the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("location: welcome.php");
    exit;
}

require_once "config.php";

// Initialize variables
$username = $password = "";
$err = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize user inputs to avoid XSS and SQL Injection
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($username) || empty($password)) {
        $err = "Please enter username and password";
    }

    if (empty($err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a session and redirect
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;

                            header("location: welcome.php");
                            exit;
                        } else {
                            $err = "Incorrect password. Please try again.";
                        }
                    }
                } else {
                    $err = "No account found with that username.";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Login Form</title>
</head>
<body>
<div class="container mt-4">
    <h3>Please Login Here:</h3>
    <hr>
    <?php if (!empty($err)) : ?>
        <div class="alert alert-danger"><?php echo $err; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-group">
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">Show</button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <!-- Register Link -->
    <div class="mt-3">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<!-- JavaScript to toggle password visibility -->
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            if (target.type === "password") {
                target.type = "text";
                this.textContent = "Hide";
            } else {
                target.type = "password";
                this.textContent = "Show";
            }
        });
    });
</script>
</body>
</html>
