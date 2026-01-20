<?php
include('config.php');

if (isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $middleName = $conn->real_escape_string($_POST['middleName']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $age = $conn->real_escape_string($_POST['age']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    $email = $conn->real_escape_string($_POST['email']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $adress = $conn->real_escape_string($_POST['adress']);
    $image = $_FILES['image']['name'];
    $target = 'uploads/' . basename($image);

    // Base URL where images will be accessible
    $base_url = 'uploads/'; // Replace with your actual base URL
    $image_url = $base_url . basename($image);

    // Check if the uploads directory exists, if not create it
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO formList (name, middleName, surname, age, gender, phoneNumber, email, birthday, adress, image) VALUES ('$name', '$middleName', '$surname', '$age', '$gender', '$phoneNumber', '$email', '$birthday', '$adress', '$image_url')";
        if ($conn->query($sql) === TRUE) {
            echo "Image uploaded and form submitted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload image";
    }

    $conn->close();
}
?>
