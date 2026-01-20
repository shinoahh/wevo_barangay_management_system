<?php
session_start();
include('config.php');

$search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';

// Query to fetch all formList data or search results
$query = "SELECT * FROM `formList`";
if ($search) {
    $query .= " WHERE 
        `name` LIKE '%$search%' OR 
        `middleName` LIKE '%$search%' OR 
        `surname` LIKE '%$search%' OR 
        `age` LIKE '%$search%' OR 
        `gender` LIKE '%$search%' OR 
        `phoneNumber` LIKE '%$search%' OR 
        `email` LIKE '%$search%' OR 
        `birthday` LIKE '%$search%' OR 
        `address` LIKE '%$search%' OR 
        `image` LIKE '%$search%'";
}

$formListResult = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);

    $duplicate = mysqli_query($conn, "SELECT * FROM volunteerStatus WHERE volunteerName = '$username' AND email = '$email'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script>alert('This is already processed');</script>";
    } else {
        $status = ($action == "accept") ? "accepted" : "declined";
        $sql = "INSERT INTO volunteerStatus (volunteerName, email, status) VALUES ('$username', '$email', '$status')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record $status and logged successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch accepted volunteers
$acceptedQuery = "SELECT * FROM `volunteerStatus` WHERE status = 'accepted'";
$acceptedResult = mysqli_query($conn, $acceptedQuery);

// Fetch declined volunteers
$declinedQuery = "SELECT * FROM `volunteerStatus` WHERE status = 'declined'";
$declinedResult = mysqli_query($conn, $declinedQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="assets/css/admin.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebarContainer">
            <div class="adminTitle">Admin Dashboard</div>
            <div class="sidebarBtnContainer">
                <a href="#" id="btnVolInformation">Volunteer Information</a>
                <a href="#" id="btnVolAccepted">Volunteer Accepted</a>
                <a href="#" id="btnVolDeclined">Volunteer Declined</a>
                <a href="adminLogin.php" id="btnLogout">Logout</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Volunteer Information -->
            <div id="volunteerInformation" class="content-section">
                <h3>Volunteer List</h3>
                <!-- Search Form -->
                <form id="searchForm" method="POST" action="">
                    <div class="form-group">
                        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search for volunteers...">
                    </div>
                </form>

                <table class="table table-hover table-bordered">
                    
                    <tbody id="volunteerTableBody">
                        <?php
                        if (mysqli_num_rows($formListResult) > 0) {
                            while ($row = mysqli_fetch_assoc($formListResult)) {
                                echo '<tr class="volunteer-row" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-middle-name="' . $row['middleName'] . '" data-surname="' . $row['surname'] . '" data-age="' . $row['age'] . '" data-gender="' . $row['gender'] . '" data-phone="' . $row['phoneNumber'] . '" data-email="' . $row['email'] . '" data-birthday="' . $row['birthday'] . '" data-address="' . $row['adress'] . '" data-image="' . $row['image'] . '">
                                    <td>' . $row['id'] . '</td>
                                    <td><div class="gallery"><img src="' . $row['image'] . '" alt="Volunteer Image"></div></td>
                                    <td>' . $row['name'] . '</td>
                                    <td>' . $row['middleName'] . '</td>
                                    <td>' . $row['surname'] . '</td>
                                    <td>' . $row['age'] . '</td>
                                    <td>' . $row['gender'] . '</td>
                                    <td>' . $row['phoneNumber'] . '</td>
                                    <td>' . $row['email'] . '</td>
                                    <td>' . $row['birthday'] . '</td>
                                    <td>' . $row['adress'] . '</td>
                                    <td>
                                        <form method="POST" class="formListButton">  
                                            <input type="hidden" name="username" value="' . $row['name'] . '">
                                            <input type="hidden" name="email" value="' . $row['email'] . '">
                                            <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                            <button type="submit" name="action" value="decline" class="btn btn-danger btn-sm">Decline</button>
                                            <a href="mailto:' . $row['email'] . '" class="btnEmail">Email</a>
                                        </form>
                                    </td>
                                </tr>';
                            }
                        } else {
                            echo "<tr><td colspan='12'>No results found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Volunteer Accepted -->
            <div id="volunteerAccepted" class="content-section">
                <h3>Volunteer Accepted</h3>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($acceptedResult) > 0) {
                            while ($row = mysqli_fetch_assoc($acceptedResult)) {
                                echo "<tr>
                                        <td>{$row['volunteerName']}</td>
                                        <td>{$row['email']}</td>
                                    </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Volunteer Declined -->
            <div id="volunteerDeclined" class="content-section">
                <h3>Volunteer Declined</h3>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr class="trDeclined">
                          <th>Name</th>
                          <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($declinedResult) > 0) {
                            while ($row = mysqli_fetch_assoc($declinedResult)) {
                                echo "<tr>
                                        <td>{$row['volunteerName']}</td>
                                        <td>{$row['email']}</td>
                                    </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="assets/js/admin.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".volunteer-row").forEach(function(row) {
            row.addEventListener("click", function() {
                let id = this.getAttribute("data-id");
                let name = this.getAttribute("data-name");
                let middleName = this.getAttribute("data-middle-name");
                let surname = this.getAttribute("data-surname");
                let age = this.getAttribute("data-age");
                let gender = this.getAttribute("data-gender");
                let phone = this.getAttribute("data-phone");
                let email = this.getAttribute("data-email");
                let birthday = this.getAttribute("data-birthday");
                let address = this.getAttribute("data-address");
                let image = this.getAttribute("data-image");

                document.getElementById("volunteerId").innerText = id;
                document.getElementById("volunteerName").innerText = name;
                document.getElementById("volunteerMiddleName").innerText = middleName;
                document.getElementById("volunteerSurname").innerText = surname;
                document.getElementById("volunteerAge").innerText = age;
                document.getElementById("volunteerGender").innerText = gender;
                document.getElementById("volunteerPhone").innerText = phone;
                document.getElementById("volunteerEmail").innerText = email;
                document.getElementById("volunteerBirthday").innerText = birthday;
                document.getElementById("volunteerAddress").innerText = address;
                document.getElementById("volunteerImage").src = image;

                document.getElementById("floatingForm").style.display = "block";
            });
        });

        document.querySelector(".close-btn").addEventListener("click", function() {
            document.getElementById("floatingForm").style.display = "none";
        });
    });
    </script>
</body>
</html>
