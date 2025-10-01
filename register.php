<?php
include_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_name = trim($_POST['owner_name']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if (empty($owner_name) || empty($phone) || empty($password)) {
        $message = '<div class="alert alert-error">All fields are required!</div>';
    } else {
        // Check if phone already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = '<div class="alert alert-error">Phone number already registered!</div>';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (owner_name, phone, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $owner_name, $phone, $hashed_password);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Registration successful! You can now <a href="login.php">Login</a></div>';
            } else {
                $message = '<div class="alert alert-error">Error: ' . $conn->error . '</div>';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Business Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="register-container">
        <button class="back-btn" onclick="window.location.href='index.php'">
            <i class="fas fa-arrow-left"></i>
        </button>

        <div class="register-header">
            <h1 class="register-title">Owner Registration</h1>
            <p class="register-subtitle">Register your business owner account</p>
        </div>

        <?php echo $message; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="owner_name" class="form-label">Owner Name</label>
                <input type="text" id="owner_name" name="owner_name" class="form-input" required 
                       value="<?php echo isset($_POST['owner_name']) ? htmlspecialchars($_POST['owner_name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-input" required 
                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
