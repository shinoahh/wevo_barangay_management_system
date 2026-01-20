<?php
require "config.php";

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Server-side validation for password
    if (!preg_match('/^[a-zA-Z0-9]{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and contain no special characters');</script>";
    } else {
        $duplicate = mysqli_query($conn, "SELECT * FROM account WHERE email = '$email'");

        if (mysqli_num_rows($duplicate) > 0) {
            echo "<script>alert('Already have an account');</script>";
        } else {
            if ($password == $confirmPassword) {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO account (email, username, password) VALUES ('$email', '$username', '$hashed_password')";
                if (mysqli_query($conn, $query)) {
                    echo "<script>alert('Registration success');</script>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "<script>alert('Passwords do not match');</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Registration</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/registration.css" rel="stylesheet">
  <script>
    function validateForm() {
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirmPassword").value;
      var passwordPattern = /^[a-zA-Z0-9]{8,}$/;

      if (!passwordPattern.test(password)) {
        alert("Password must be at least 8 characters long and contain no special characters.");
        return false;
      }

      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
      }

    
      return true;
    }
  </script>
</head>
<body>

    <div id="container" class="row justify-content-center">
        <div class="col-md-6">
          <div id="mainContainer" class="card">
            <div id="title_container" class="card-header">
              <h1>Register</h1>
            <div class="card-body">
              <form action="" method="POST" autocomplete="off" onsubmit="return validateForm()">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="confirmPassword">Confirm password</label>
                  <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                <div class="text-center mt-3">
                  <a href="login.php">Already have an account?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>
</html>
