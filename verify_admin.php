<?php
session_start();
require "config.php";

if (isset($_POST["submit"])) {
    $username = $_POST["usernameAndEmail"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Debugging: Use error_log instead of echo/alert
    error_log('Submitted Username/Email: ' . $username);
    error_log('Submitted Password: ' . $password);
    error_log('Submitted OTP: ' . $otp);

    // Prepared statement to fetch user details from the database
    $stmt = $conn->prepare("SELECT * FROM adminUser WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging: Check if the query returned any rows
    error_log('Query executed, number of rows: ' . $result->num_rows);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Debugging: Print fetched data
        error_log('Fetched Username: ' . $row["username"] . ' / Fetched Email: ' . $row["email"]);
        error_log('Fetched Password Hash: ' . $row["password"]);

        // Check password match using password_verify
        if (password_verify($password, $row["password"])) {
            // Debugging: Check the OTP
            error_log('Session OTP: ' . $_SESSION["otp"]);

            if ($otp == $_SESSION["otp"]) {
                // OTP verified successfully
                $_SESSION["login"] = true;
                $_SESSION["id"] = $row["id"];  // Use fetched user ID instead of session OTP ID
                unset($_SESSION["otp"]);
                unset($_SESSION["otp_id"]);
                header("Location: insideWeb.php");
                exit();
            } else {
                error_log('Invalid OTP');
                echo "<script>alert('Invalid OTP');</script>";
            }
        } else {
            error_log('Wrong password');
            echo "<script>alert('Wrong password');</script>";
        }
    } else {
        error_log('User is not registered');
        echo "<script>alert('User is not registered');</script>";
    }

    // Close statement
    $stmt->close();
}
?>
