<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config.php');

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

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
        `adress` LIKE '%$search%' OR 
        `image` LIKE '%$search%'";
}

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
} else {
    if (mysqli_num_rows($result) > 0) {
        echo '<!DOCTYPE html>
              <html lang="en">
              <head>
                  <meta charset="UTF-8">
                  <meta http-equiv="X-UA-Compatible" content="IE=edge">
                  <meta name="viewport" content="width=device-width, initial-scale=1">
                  <title>Search Volunteers</title>
                  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
                  <link rel="stylesheet" href="search.css">
                  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
              </head>
              <body>
                      <table>
                          <thead>
                              <tr>
                                  <th>ID</th>
                                  <th>Image</th>
                                  <th>Name</th>
                                  <th>Middle Name</th>
                                  <th>Surname</th>
                                  <th>Age</th>
                                  <th>Gender</th>
                                  <th>Phone Number</th>
                                  <th>Email</th>
                                  <th>Birthday</th>
                                  <th>Address</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr class="volunteer-row" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-middle-name="' . $row['middleName'] . '" data-surname="' . $row['surname'] . '" data-age="' . $row['age'] . '" data-gender="' . $row['gender'] . '" data-phone="' . $row['phoneNumber'] . '" data-email="' . $row['email'] . '" data-birthday="' . $row['birthday'] . '" data-address="' . $row['adress'] . '" data-image="' . $row['image'] . '">
                      <td>' . $row['id'] . '</td>
                      <td><div class="gallery">
                              <img src="' . $row['image'] . '" alt="Volunteer Image">
                          </div></td>
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
        echo '</tbody>
                  </table>
                  <div id="floatingForm" class="floating-form">
                      <span class="close-btn">&times;</span>
                      <h3>Volunteer Details</h3>
                      <p><strong>ID:</strong> <span id="volunteerId"></span></p>
                      <p><strong>Name:</strong> <span id="volunteerName"></span></p>
                      <p><strong>Middle Name:</strong> <span id="volunteerMiddleName"></span></p>
                      <p><strong>Surname:</strong> <span id="volunteerSurname"></span></p>
                      <p><strong>Age:</strong> <span id="volunteerAge"></span></p>
                      <p><strong>Gender:</strong> <span id="volunteerGender"></span></p>
                      <p><strong>Phone Number:</strong> <span id="volunteerPhone"></span></p>
                      <p><strong>Email:</strong> <span id="volunteerEmail"></span></p>
                      <p><strong>Birthday:</strong> <span id="volunteerBirthday"></span></p>
                      <p><strong>Address:</strong> <span id="volunteerAddress"></span></p>
                      <p><strong>Image:</strong> <img id="volunteerImage" src="" alt="Volunteer Image" style="width:100px;height:auto;"></p>
                  </div>
                  <script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed");
    
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

            console.log({ id, name, middleName, surname, age, gender, phone, email, birthday, address, image });

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
          </html>';
    } else {
        echo "<tr><td colspan='12'>No results found</td></tr>";
    }
}
$conn->close();
?>
