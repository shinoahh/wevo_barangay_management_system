<?php
session_start();
require "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["send_otp"])) {
    $usernameAndEmail = $_POST["usernameAndEmail"];
    
    if (!empty($usernameAndEmail)) {
        $result = mysqli_query($conn, "SELECT * FROM account WHERE email = '$usernameAndEmail' OR username = '$usernameAndEmail'");
        $row = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) > 0) {
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
                $mail->addAddress($row["email"]);
                $mail->isHTML(true);
                $mail->Subject = 'WeVo OTP';
                $mail->Body = 'Your OTP code is ' . $otp;
                $mail->send();
                echo "OTP sent successfully";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "User is not registered.";
        }
    } 
    exit;
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
  <link href="assets/css/login.css" rel="stylesheet">
  <script src="jquery-3.3.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  

    <div id="container" class="row justify-content-center">
      <div class="col-md-6">
        <div id= "mainContainer" class="card ">
          <div class="card-header">
            <h1 id="login">Login</h1>
          
          
          <div class="card-body ">
            <form id="otpForm" action="verify_login.php" method="POST">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="usernameAndEmail" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="otp">OTP</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
              </div>
              <button name="submit" type="submit" class="btn btn-primary btn-block custom-button">Login</button>
              <div class="form-group">
                <button id="sendOtpBtn" name="send_otp" type="button" class="btn btn-primary btn-block mt-3" onclick="sendOtp()">Request OTP</button>
                <div id="timer" class="mt-2"></div>
              </div>
              <div id="register_container" class="d-flex justify-content-start mt-3">
              <p>Doesn’t have any account yet?</p>
              <a href="registration.php">Sign up</a>
              </div>
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
  function sweet() {
    Swal.fire({
        title: 'OTP Sent!',
        text: 'Check your email for the OTP.',
        icon: 'success',
        showConfirmButton: true, // Show OK button
        allowOutsideClick: false, // Prevent clicking outside
        allowEscapeKey: false, // Prevent Esc key from closing
    });
}

function warning() {
  Swal.fire({
  title: "Email and Password is Required",
  text: "Please fill the Email and Password first",
  icon: "warning",
  showConfirmButton: true, // Show OK button
  allowOutsideClick: false, // Prevent clicking outside
  allowEscapeKey: false, // Prevent Esc key from closing
});
}

    function sendOtp() {
    let sendOtpBtn = document.getElementById('sendOtpBtn');
    
    let emailInput = $('#email').val().trim();
    let passwordInput = $('#password').val().trim();
    if (emailInput && passwordInput === '') {
       warning();
        return;
    }

    $.ajax({
        type: 'POST',
        url: '', // Use the same file
        data: $('#otpForm').serialize() + '&send_otp=1',
        success: function(response) {
            if (response.includes("OTP sent successfully")) {
                sweet(); 
                startTimer(3 * 60);
            } else {
                alert(response);
            }
        },
        error: function(xhr, status, error) {
            alert("Error: " + error);
        }
    });
}


  function startTimer(duration) {
    let timer = duration, minutes, seconds;
    let display = document.getElementById('timer');

    sendOtpBtn.disabled = true; // Disable button

    let interval = setInterval(function () {
      minutes = parseInt(timer / 60, 10);
      seconds = parseInt(timer % 60, 10);

      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      display.textContent = minutes + ":" + seconds;
     

      if (--timer < 0) {
        clearInterval(interval);
        display.textContent = "Resend OTP?";
        sendOtpBtn.disabled = false; // Re-enable button
      }
    }, 1000);
  }

  
</script>
</body>
</html>
