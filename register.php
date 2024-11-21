<?php
// Created and Programmed by: LeoTechWhiz / Roshan Gautam


require_once "config.php";

// Initialize variables
$username = $email = $phone = $dob = $password = $confirm_password = "";
$username_err = $email_err = $phone_err = $dob_err = $password_err = $confirm_password_err = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize user inputs to avoid XSS and SQL Injection
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Validate Username
    if (empty($username)) {
        $username_err = "Username cannot be blank";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Validate Email
    if (empty($email)) {
        $email_err = "Email cannot be blank";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already registered";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Validate Phone Number
    if (empty($phone)) {
        $phone_err = "Phone number cannot be blank";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $phone_err = "Phone number must be a 10-digit number without special characters";
    }

    // Validate Date of Birth
    if (empty($dob)) {
        $dob_err = "Date of Birth cannot be blank";
    } else {
        $dob_timestamp = strtotime($dob);
        if ($dob_timestamp === false) {
            $dob_err = "Invalid date format";
        } else {
            $age = (date("Y") - date("Y", $dob_timestamp));
            if ($age < 18) {
                $dob_err = "You must be over 18 years old";
            }
        }
    }

    // Validate Password
    if (empty($password)) {
        $password_err = "Password cannot be blank";
    } elseif (strlen($password) < 8) {
        $password_err = "Password must be at least 8 characters long";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $password_err = "Password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $password_err = "Password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $password_err = "Password must contain at least one number";
    } elseif (!preg_match('/[\W_]/', $password)) {
        $password_err = "Password must contain at least one special character";
    }

    // Confirm Password
    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm your password";
    } elseif ($password !== $confirm_password) {
        $confirm_password_err = "Passwords do not match";
    }

    // If no errors, insert the user data into the database
    if (empty($username_err) && empty($email_err) && empty($phone_err) && empty($dob_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO users (username, email, phone, dob, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $phone, $dob, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "Something went wrong... cannot redirect!";
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
    <title>Registration Form</title>
  </head>
  <body>
    <div class="container mt-4">
      <h3>Please Register Here:</h3>
      <hr>

      <!-- Success Message -->
      <?php if (!empty($success_msg)) : ?>
        <div class="alert alert-success">
          <?php echo $success_msg; ?>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <!-- Username Field -->
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Username">
          <span class="text-danger"><?php echo $username_err; ?></span>
        </div>

        <!-- Email Address Field -->
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
          <span class="text-danger"><?php echo $email_err; ?></span>
        </div>

        <!-- Phone Number Field -->
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number">
          <span class="text-danger"><?php echo $phone_err; ?></span>
        </div>

        <!-- Date of Birth Field -->
        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <input type="date" class="form-control" name="dob" id="dob">
          <span class="text-danger"><?php echo $dob_err; ?></span>
        </div>

        <!-- Password and Confirm Password Fields -->
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="password">Password</label>
            <div class="input-group">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">Show</button>
              </div>
            </div>
            <span class="text-danger"><?php echo $password_err; ?></span>
          </div>
          <div class="form-group col-md-6">
            <label for="confirm_password">Confirm Password</label>
            <div class="input-group">
              <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#confirm_password">Show</button>
              </div>
            </div>
            <span class="text-danger"><?php echo $confirm_password_err; ?></span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Script to toggle password visibility -->
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
