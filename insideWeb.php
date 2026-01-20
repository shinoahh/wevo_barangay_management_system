<?php
require "config.php";

session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["id"];
$query = "SELECT * FROM account WHERE id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$formSubmitted = false;
$formSubmittedMessage = "";

$formCheckQuery = "SELECT * FROM formList WHERE email = '{$user['email']}'";
$formCheckResult = mysqli_query($conn, $formCheckQuery);

if (mysqli_num_rows($formCheckResult) > 0) {
    $formSubmitted = true;

    // Fetch the status from volunteerStatus table
    $statusQuery = "SELECT status FROM volunteerStatus WHERE email = '{$user['email']}'";
    $statusResult = mysqli_query($conn, $statusQuery);

    if ($statusResult && mysqli_num_rows($statusResult) > 0) {
        $statusData = mysqli_fetch_assoc($statusResult);
        $status = $statusData['status'];

        // Update the message based on the status
        if ($status == 'accepted') {
            $formSubmittedMessage = "Your account has been accepted.";
        }elseif ($status == 'declined') {
            $formSubmittedMessage = "Your account has been declined.";
        } else {
            $formSubmittedMessage = "Your account is under verification process.";
        }
    } else {
        $formSubmittedMessage = "Your account is under verification process.";
    }
}

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $middleName = $_POST["middleName"];
    $surname = $_POST["surname"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $birthday = $_POST["birthday"];
    $adress = $_POST["adress"]; // corrected variable name
    $image = $_FILES['image']['name'];
    $target = 'uploads/' . basename($image);
    
    
    $base_url = 'uploads/'; // Replace with your actual base URL
    $image_url = $base_url . basename($image);
    // Calculate age from birthday
    $birthdate = new DateTime($birthday);
    $today = new DateTime('today');
    $calculatedAge = $birthdate->diff($today)->y;

    // Check if entered age matches calculated age
    if ($age != $calculatedAge) {
        echo "<script>alert('Age does not match the birthday entered. Please check and try again.');</script>";
    } else {
        $duplicate = mysqli_query($conn, "SELECT * FROM formList WHERE email = '$email' OR (name = '$name' AND surname = '$surname')");

        if (mysqli_num_rows($duplicate) > 0) {
            echo "<script>alert('Form already submitted');</script>";
        } else {
          
              if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
              }
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO formList (name, middleName, surname, age, gender, phoneNumber, email, birthday, adress, image) VALUES ('$name', '$middleName', '$surname', '$age', '$gender', '$phoneNumber', '$email', '$birthday', '$adress', '$image_url')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('image uploaded and form successfully submitted');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload image";
    }
            
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Form submitted');</script>";
                $formSubmitted = true;
                $formSubmittedMessage = "Your account is under verification process.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch formList data if form is already submitted
if ($formSubmitted) {
    $query = "SELECT * FROM formList WHERE email = '{$user['email']}'";
    $result = mysqli_query($conn, $query);
    $formData = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logout-container {
            position: static;
            top: 5px;
            right: 10px;
            z-index: 1000; /* Ensure it's above other content */
        }
        
        #container{
          display: flex;
          flex-direction: column;
        }
        
        #logo {
            height: 30px;
            border-radius: 20px;
            margin-left:10px;
        }
        
        #header {
            padding: 10px!important;
            position:sticky!important;
        }
        
        #my_camera {
            width: 320px;
            height: 240px;
            border: 1px solid black;
            float: left; /* Image preview on top left */
            margin-right: 20px; /* Margin for spacing */
        }
        
        #results {
            padding: 20px;
            border: 1px solid;
            background: #f0f0f0;
            clear: both; /* Clear floating elements */
        }
        
        #header{
          text-align: center;
          align-items: center;
          color:white;
        }
        
        #image{
          width:80px;
          height:80px;
          margin-right:16px;
          border-radius:4px;
          object-fit:cover ;
        }
        
        #uploadImage{
          display:flex;
          justify-content: space-between;
          align-items: center;
          text-align: center;
          margin-top:20px;
        }
        
        label{
          color:white!important;
        }
        
        p{
          color: white;
        }
        
        h2{
          border-radius: 3px!important;
        }
        
        #logoutIcon{
          width:10px;
          align-items: center;
          text-align: center;
          margin:2px;
          padding:0;
          margin-bottom:3px;
        }
        
        @media (max-width: 768px) {
            .logout-container {
                position: static;
                top: 5px;
                right: 10px;
                z-index: 1000; /* Ensure it's above other content */
            }
        
            #logo {
                height: 30px;
                border-radius: 20px;
                margin-left:10px;
            }
        
            #header {
                padding: 10px!important;
                position:sticky!important;
            }
        
            #my_camera {
                width: 320px;
                height: 240px;
                border: 1px solid black;
                float: left; /* Image preview on top left */
                margin-right: 20px; /* Margin for spacing */
            }
        
            #results {
                padding: 20px;
                border: 1px solid;
                background: #f0f0f0;
                clear: both; /* Clear floating elements */
            }
        
            #header{
              text-align: center;
              align-items: center;
              color:white;
            }
        
            #image{
              width:80px;
              height:80px;
              margin-right:16px;
              border-radius:4px;
              object-fit:cover ;
            }
        
            #uploadImage{
              display:flex;
              justify-content: space-between;
              align-items: center;
              text-align: center;
              margin-top:20px;
            }
        
            label{
              color:white!important;
            }
        
            p{
              color: white;
            }
        
            h2{
              border-radius: 3px!important;
            }
        
            #logoutIcon{
              width:10px;
              align-items: center;
              text-align: center;
              margin:2px;
              padding:0;
              margin-bottom:3px;
            }
        }
         
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="header">
        <img id="logo" src="assets/img/logo.jpg" alt="" title="">
        <div class="logout-container">
            <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-danger">Logout<img id ="logoutIcon" src="webIcon/logout.png"/></button>
            </form>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div id ="" class="jumbotron bg-dark">
            <h3 id = "header" class="display-4">Welcome <?php echo htmlspecialchars($user["username"]); ?></h3>

            <?php if (!$formSubmitted): ?>
                <p class="lead">Please fill the registration below for further verification.</p>
                <hr class="my-4">
                <form id="registrationForm" action="" method="POST" enctype="multipart/form-data">
                    <!-- Your form inputs -->
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="middleName">Middle Name</label>
                            <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="surname">Surname</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" id="age" name="age" placeholder="Age" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Gender</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="male" name="gender" value="Male" required>
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="female" name="gender" value="Female" required>
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="number" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="+63-9***********" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="birthdate">Birthday</label>
                            <input type="date" class="form-control" id="birthdate" name="birthday" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="adress">Address</label>
                            <input type="text" class="form-control" id="adress" name="adress" placeholder="Address" required>
                        </div>
                    </div>

                    <label for="image">Upload Image:</label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <br>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php else: ?>
                <p class="lead">Your submitted details:</p>
                <hr class="my-4">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Name</label>
                        <h2 class="form-control" id="name"><?php echo htmlspecialchars($formData["name"]); ?></h2>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="middleName">Middle Name</label>
                        <h2 class="form-control" id="middleName"><?php echo htmlspecialchars($formData["middleName"]); ?></h2>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="surname">Surname</label>
                        <h2 class="form-control" id="surname"><?php echo htmlspecialchars($formData["surname"]); ?></h2>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="age">Age</label>
                        <h2 class="form-control" id="age"><?php echo htmlspecialchars($formData["age"]); ?></h2>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="gender">Gender</label>
                        <h2 class="form-control" id="gender"><?php echo htmlspecialchars($formData["gender"]); ?></h2>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phoneNumber">Phone Number</label>
                        <h2 class="form-control" id="phoneNumber"><?php echo htmlspecialchars($formData["phoneNumber"]); ?></h2>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <h2 class="form-control" id="email"><?php echo htmlspecialchars($formData["email"]); ?></h2>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="birthday">Birthday</label>
                        <h2 class="form-control" id="birthday"><?php echo htmlspecialchars($formData["birthday"]); ?></h2>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="adress">Address</label>
                        <h2 class="form-control" id="adress"><?php echo htmlspecialchars($formData["adress"]); ?></h2>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Uploaded Image</label><br>
                    <img id="image" src="<?php echo htmlspecialchars($formData['image']); ?>" alt="Uploaded Image" class="img-thumbnail">
                </div>

                <p class="lead"><?php echo $formSubmittedMessage; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
