<?php
// Include database configuration (Make sure this is correct)
include_once 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $shop_name = trim($_POST['shop_name']);
    $owner_name = trim($_POST['owner_name']);
    $address = trim($_POST['address']);
    $contact_info = trim($_POST['contact_info']);

    // Handle file uploads for shop image and owner photo
    $shop_image = $_FILES['shop_image']['name'];
    $owner_photo = $_FILES['owner_photo']['name'];

    $upload_directory = 'uploads/';
    // Ensure uploads directory exists
    if (!is_dir($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }

    // Move files to the uploads directory (if uploaded)
    $shop_image_path = '';
    $owner_photo_path = '';
    if (!empty($shop_image) && is_uploaded_file($_FILES['shop_image']['tmp_name'])) {
        $shop_image_path = $upload_directory . basename($shop_image);
        move_uploaded_file($_FILES['shop_image']['tmp_name'], $shop_image_path);
    }
    if (!empty($owner_photo) && is_uploaded_file($_FILES['owner_photo']['tmp_name'])) {
        $owner_photo_path = $upload_directory . basename($owner_photo);
        move_uploaded_file($_FILES['owner_photo']['tmp_name'], $owner_photo_path);
    }

    // Insert data into the database using prepared statement
    $stmt = $conn->prepare("INSERT INTO business_info (shop_name, owner_name, address, contact_info, shop_image, owner_photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $shop_name, $owner_name, $address, $contact_info, $shop_image_path, $owner_photo_path);
    if ($stmt->execute()) {
        echo "Business information added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Info</title>
</head>
<body>
    <h1>Business Information</h1>
    <!-- Form to add business info -->
    <form action="business.php" method="POST" enctype="multipart/form-data">
        <label for="shop_name">Shop Name:</label>
        <input type="text" id="shop_name" name="shop_name" required><br><br>

        <label for="owner_name">Owner Name:</label>
        <input type="text" id="owner_name" name="owner_name" required><br><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea><br><br>

        <label for="contact_info">Contact Info:</label>
        <input type="text" id="contact_info" name="contact_info"><br><br>

        <label for="shop_image">Shop Image:</label>
        <input type="file" id="shop_image" name="shop_image" required><br><br>

        <label for="owner_photo">Owner Photo:</label>
        <input type="file" id="owner_photo" name="owner_photo" required><br><br>

        <button type="submit">Save Business Info</button>
    </form>

    <br>
    <!-- Navigation buttons for Login and Registration -->
    <a href="login.php">Login</a> | 
    <a href="register.php">Register</a>
</body>
</html>
