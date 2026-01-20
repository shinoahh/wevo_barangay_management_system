<?php
require "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["send_otp"])) {
    $username = $_POST["username"];
    
    if (!empty($username)) {
        $result = mysqli_query($conn, "SELECT * FROM adminUser WHERE username = '$username'");
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (isset($row["username"])) {
                $otp = rand(100000, 999999);
                $_SESSION["otp"] = $otp;
                $_SESSION["otp_id"] = $row["id"];
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'wevoearist2024@gmail.com';
                    $mail->Password = 'xnwzeezxbmcauhlm';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    // Disable SSL verification (for testing purposes only)
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    $mail->setFrom('wevoearist2024@gmail.com', 'wevo');
                    $mail->addAddress($row["username"]); // Assuming username needs to be formatted into an email address
                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP Code';
                    $mail->Body = 'Your OTP code is ' . $otp;
                    $mail->send();
                    // Output a JSON response
                    echo json_encode(["status" => "success", "message" => "OTP sent successfully."]);
                } catch (Exception $e) {
                    // Output a JSON response
                    echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Username is not registered."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "User is not registered."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username input is empty. Please provide a valid username."]);
    }
    exit(); // End script execution after handling AJAX request
}

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $result = mysqli_query($conn, "SELECT * FROM adminUser WHERE username = '$username'");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row["password"]) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            header("Location: admin.php");
            exit();
        } else {
            echo "<script>alert('wrong password');</script>";
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
  <title>Login</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/adminLogin.css" rel="stylesheet">
</head>
<body>
  <div id="container" class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h2>Admin Login</h2>
          </div>
          <div class="card-body">
            <form action="" method="POST" id="otpForm">
              <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="otp">OTP:</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
              </div>
              <div class="form-group">
                <button id="sendOtpBtn" name="send_otp" type="button" class="btn btn-primary btn-block" onclick="sendOtp()">Send OTP</button>
                <div id="timer" class="mt-2"></div>
              </div>
              <button name="submit" type="submit" class="btn btn-primary btn-block custom-button">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
  function sendOtp() {
    let usernameInput = $('#username').val().trim();
    if (usernameInput === '') {
      alert("Please enter your username.");
      return;
    }

    $.ajax({
      type: 'POST',
      url: '', // Since the PHP script is in the same file
      data: $('#otpForm').serialize() + '&send_otp=1', // Ensure the send_otp is set
      success: function(response) {
        try {
          const jsonResponse = JSON.parse(response);
          alert(jsonResponse.message);
          if (jsonResponse.status === "success") {
            startTimer(3 * 60);
          }
        } catch (e) {
          alert("Error parsing response: " + response);
        }
      },
      error: function(xhr, status, error) {
        alert("Error: " + error);
      }
    });
  }

  function startTimer(duration) {
    var timer = duration, minutes, seconds;
    var display = document.getElementById('timer');
    var sendOtpBtn = document.getElementById('sendOtpBtn');

    sendOtpBtn.disabled = true; // Disable button

    var interval = setInterval(function () {
      minutes = parseInt(timer / 60, 10);
      seconds = parseInt(timer % 60, 10);

      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      display.textContent = minutes + ":" + seconds;

      if (--timer < 0) {
        clearInterval(interval);
        display.textContent = "Time's up!";
        sendOtpBtn.disabled = false; // Re-enable button
      }
    }, 1000);
  }
  </script>
</body>
</html>
