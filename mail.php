<?php
require "config.php";
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Create an instance; passing `true` enables exceptions
if (isset($_POST["send"])) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->Debugoutput = 'html'; // Set debug output to HTML format
        $mail->isSMTP();                              // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';         // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                     // Enable SMTP authentication
        $mail->Username   = 'wevoearist2024@gmail.com'; // SMTP username
        $mail->Password   = 'xnwzeezxbmcauhlm';       // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                      // TCP port to connect to

        // Disable certificate verification (temporary workaround)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom($_POST["email"], $_POST["name"]); // Sender Email and name
        $mail->addAddress('wevoearist2024@gmail.com');   // Add a recipient email  
        $mail->addReplyTo($_POST["email"], $_POST["name"]); // Reply to sender email

        // Content
        $mail->isHTML(true);                        // Set email format to HTML
        $mail->Subject = $_POST["subject"];         // Email subject headings
        $mail->Body    = $_POST["message"];         // Email message

        // Send email
        $mail->send();
        echo "
        <script> 
            alert('Message was sent successfully!');
            document.location.href = 'index.php';
        </script>
        ";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600);
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-weight: 300; font-size: 12px; line-height: 30px; color: #272727; background: rgb(25, 199, 155); }
        .container { max-width: 400px; width: 100%; margin: 0 auto; position: relative; }
        #contact input { font: 400 12px/16px; width: 100%; border: 1px solid #CCC; background: #FFF; margin: 10 5px; padding: 10px; }
        h1 { margin-bottom: 30px; font-size: 30px; }
        #contact { background: #F9F9F9; padding: 25px; margin: 50px 0; }
        fieldset { border: medium none !important; margin: 0 0 10px; min-width: 100%; padding: 0; width: 100%; }
        textarea { height: 100px; max-width: 100%; resize: none; width: 100%; }
        button { cursor: pointer; width: 100%; border: none; background: rgb(17, 146, 60); color: #FFF; margin: 0 0 5px; padding: 10px; font-size: 20px; }
        button:hover { background-color: rgb(15, 95, 42); }
    </style>
</head>

<body>
    <div class="container">
        <form id="contact" action="mail.php" method="post">
            <h1>Contact Form</h1>
            <fieldset>
                <input placeholder="Your name" name="name" type="text" tabindex="1" autofocus>
            </fieldset>
            <fieldset>
                <input placeholder="Your Email Address" name="email" type="email" tabindex="2">
            </fieldset>
            <fieldset>
                <input placeholder="Type your subject line" type="text" name="subject" tabindex="4">
            </fieldset>
            <fieldset>
                <textarea name="message" placeholder="Type your Message Details Here..." tabindex="5"></textarea>
            </fieldset>
            <fieldset>
                <button type="submit" name="send" id="contact-submit">Submit Now</button>
            </fieldset>
        </form>
    </div>
</body>

</html>
