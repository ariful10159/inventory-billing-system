<?php
session_start();
// Include the database connection
include_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // Fetch user by phone
    $stmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables and redirect to dashboard
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['owner_name'] = $user['owner_name'];
            header('Location: dashboard.php');
            exit();
        } else {
            $message = '<div style="color:red;">Invalid phone or password!</div>';
        }
    } else {
        $message = '<div style="color:red;">Invalid phone or password!</div>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Business Management System</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php echo $message; ?>
        <form action="login.php" method="POST">
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit">Login</button>
        </form>
        <br>
        <a href="register.php">Don't have an account? Register here</a>
    </div>
</body>
</html>